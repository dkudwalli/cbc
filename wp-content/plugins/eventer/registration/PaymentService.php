<?php

class PaymentService
{
    public static function create_stripe_booking_result($eventer_id, $amount, array $card_credentials)
    {
        $token = isset($card_credentials['token']) ? sanitize_text_field($card_credentials['token']) : '';
        if ($token === '') {
            return new WP_Error('eventer_missing_token', esc_html__('Missing Stripe payment token.', 'eventer'));
        }

        $stripe_secret_key = eventer_get_settings('eventer_stripe_secret_key');
        $item_name = get_the_title($eventer_id);
        $currency = eventer_get_settings('eventer_paypal_currency');

        \Stripe\Stripe::setApiKey($stripe_secret_key);
        $intent = \Stripe\PaymentIntent::create(
            array(
                'payment_method_data' => array(
                    'type' => 'card',
                    'card' => array('token' => $token),
                ),
                'amount' => ($currency != 'JPY') ? ($amount * 100) : $amount,
                'currency' => $currency,
                'description' => $item_name,
                'metadata' => array(
                    'order_id' => $eventer_id . '-' . wp_rand(1000, 999999),
                ),
                'automatic_payment_methods[enabled]' => 'true',
                'automatic_payment_methods[allow_redirects]' => 'never',
                'confirm' => true,
            )
        );

        $response = $intent->jsonSerialize();
        $verified_amount_received = isset($response['amount_received']) ? $response['amount_received'] : 0;
        $verified_status = isset($response['status']) ? $response['status'] : '';
        $deducted_amount = ($currency != 'JPY') ? ($amount * 100) : $amount;
        $secret = '';
        $transaction_id = '';
        if (!empty($response['charges']['data'][0]['balance_transaction'])) {
            $transaction_id = $response['charges']['data'][0]['balance_transaction'];
        }

        $stripe_status_success = ($verified_status === 'succeeded' && (float) $verified_amount_received === (float) $deducted_amount) ? '1' : '2';
        if ($stripe_status_success !== '1') {
            $data = json_decode(eventer_generatePaymentResponse($intent), true);
            if (isset($data['payment_intent_client_secret'])) {
                $secret = $data['payment_intent_client_secret'];
            }
        }

        return array(
            'success_flag' => $stripe_status_success,
            'secret' => $secret,
            'transaction_id' => $transaction_id,
            'stripe_response' => $response,
            'verified_amount_received' => $verified_amount_received,
            'currency' => $currency,
        );
    }

    public static function confirm_pending_registration($registration_id, $secret)
    {
        $registration_id = absint($registration_id);
        if ($registration_id <= 0 || $secret === '') {
            return new WP_Error('eventer_invalid_confirmation', esc_html__('Invalid Stripe confirmation request.', 'eventer'));
        }

        $registrant = eventer_get_registrant_details('id', $registration_id);
        if (!$registrant) {
            return new WP_Error('eventer_missing_registration', esc_html__('Registration not found.', 'eventer'));
        }

        $registrant_status = strtolower(sanitize_text_field($registrant->status));
        $payment_mode = sanitize_text_field($registrant->paymentmode);
        if ($registrant_status !== 'pending' || !in_array($payment_mode, array('2', 'Stripe', 'stripe'), true)) {
            return new WP_Error('eventer_invalid_status', esc_html__('Registration is not eligible for Stripe confirmation.', 'eventer'));
        }

        $stripe_secret_key = eventer_get_settings('eventer_stripe_secret_key');
        $currency = eventer_get_settings('eventer_paypal_currency');
        \Stripe\Stripe::setApiKey($stripe_secret_key);
        $intent = \Stripe\PaymentIntent::retrieve($secret);

        try {
            $intent->confirm();
        } catch (\Exception $error) {
            return new WP_Error('eventer_stripe_confirm_failed', $error->getMessage());
        }

        $stripe_response = $intent->jsonSerialize();
        $verified_amount_received = isset($stripe_response['amount_received']) ? $stripe_response['amount_received'] : 0;
        $verified_status = isset($stripe_response['status']) ? $stripe_response['status'] : '';
        $transaction_id = !empty($stripe_response['charges']['data'][0]['balance_transaction']) ? $stripe_response['charges']['data'][0]['balance_transaction'] : '';
        $received_amount = ($currency != 'JPY') ? ($verified_amount_received / 100) : $verified_amount_received;
        $expected_amount = eventer_sanitize_decimal_value($registrant->amount);

        if ($verified_status === 'succeeded' && abs((float) $received_amount - (float) $expected_amount) < 0.01) {
            eventer_update_registrant_details(
                array(
                    'transaction_id' => $transaction_id,
                    'status' => 'Success',
                    'paypal_details' => serialize($stripe_response),
                    'paymentmode' => 'Stripe',
                    'amount' => $received_amount,
                ),
                $registration_id,
                array('%s', '%s', '%s', '%f')
            );

            return array(
                'intent' => $intent,
                'registration_code' => eventer_encode_security_registration($registration_id, 9, 8),
            );
        }

        if ($verified_status === 'succeeded') {
            return new WP_Error('eventer_amount_mismatch', esc_html__('Stripe amount verification failed.', 'eventer'));
        }

        return array(
            'intent' => $intent,
            'registration_code' => '',
        );
    }
}
