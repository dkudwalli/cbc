<?php
function eventer_registration_allowed_column($col = 'id')
{
  $allowed_columns = array(
    'id',
    'order_id',
  );

  return in_array($col, $allowed_columns, true) ? $col : 'id';
}

function eventer_registration_ticket_allowed_column($col = 'reg_id')
{
  $allowed_columns = array(
    'id',
    'reg_id',
    'event_id',
  );

  return in_array($col, $allowed_columns, true) ? $col : 'reg_id';
}

function addRegistration($data)
{
  global $wpdb;
  $table_name = $wpdb->prefix . "eventer_registrations";
  $wpdb->query(
    $wpdb->prepare(
      "INSERT INTO $table_name
      (booking_id, user_id, order_id, reg_date, transaction_id, paymentmode, reg_status, reg_amount)
      VALUES ( %d, %d, %d, %s, %s, %s, %s, %s )",
      array($data['booking_id'], $data['user_id'], $data['order_id'], $data['reg_date'], $data['transaction_id'], $data['paymentmode'], $data['reg_status'], $data['reg_amount'])
    )
  );
  return $wpdb->insert_id;
}

function updateRegistration($regId, $data, $type)
{
  global $wpdb;
  $tableName = $wpdb->prefix . "eventer_registrations";
  $wpdb->update($tableName, $data, array('id' => $regId), $type, array('%d'));
}

function getRegistration($regId = null, $limit = 10, $offset = 0, $col = 'id')
{
  global $wpdb;
  $tableName = $wpdb->prefix . "eventer_registrations";
  $limit = max(1, absint($limit));
  $offset = max(0, absint($offset));
  if (empty($regId)) {
    $registrations = $wpdb->get_results(
      $wpdb->prepare("SELECT * FROM $tableName ORDER BY ID DESC LIMIT %d, %d", $offset, $limit),
      ARRAY_A
    );
    return $registrations;
  } else {
    $col = eventer_registration_allowed_column($col);
    $registration = $wpdb->get_row(
      $wpdb->prepare("SELECT * FROM $tableName WHERE {$col} = %d", absint($regId)),
      OBJECT
    );
    return $registration;
  }
}

function addTickets($data)
{
  global $wpdb;
  $table_name = $wpdb->prefix . "eventer_registration_tickets";
  $wpdb->query(
    $wpdb->prepare(
      "INSERT INTO $table_name
      ( reg_id, qr_code, ticket_name, user_name, type, ticket_date, venue, event_id, price, ticket_status)
      VALUES ( %d, %s, %s, %s, %s, %s, %s, %s, %s, %s )",
      array($data['reg_id'], $data['qr_code'], $data['ticket_name'], $data['user_name'], $data['type'], $data['ticket_date'], $data['venue'], $data['event_id'], $data['price'], $data['ticket_status'])
    )
  );
  return $wpdb->insert_id;
}

function updateTicket($ticketId, $data, $type)
{
  global $wpdb;
  $tableName = $wpdb->prefix . "eventer_registration_tickets";
  $wpdb->update($tableName, $data, array('id' => $ticketId), $type, array('%d'));
}

function getRegistrationTickets($regId = null, $limit = 20, $offset = 0, $col = 'reg_id')
{
  global $wpdb;
  $tableName = $wpdb->prefix . "eventer_registration_tickets";
  if (!empty($regId)) {
    $limit = max(1, absint($limit));
    $offset = max(0, absint($offset));
    $col = eventer_registration_ticket_allowed_column($col);
    $registrationTickets = $wpdb->get_results(
      $wpdb->prepare("SELECT * FROM $tableName WHERE {$col} = %d ORDER BY ID DESC LIMIT %d, %d", absint($regId), $offset, $limit),
      OBJECT
    );
    return $registrationTickets;
  }
  return false;
}

function getRegistrants($eventId = null, $ticketDate = null)
{
  global $wpdb;
  $tableName = $wpdb->prefix . "eventer_registration_tickets";
  if (!empty($eventId) && !empty($ticketDate)) {
    $registrationTickets = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT user_name as 'name', reg_id as 'id', reg_id as 'show', ticket_status as 'checkin' FROM $tableName WHERE event_id = %d AND ticket_date = %s GROUP BY reg_id",
        absint($eventId),
        sanitize_text_field($ticketDate)
      )
    );
    return $registrationTickets;
  } elseif (!empty($eventId)) {
    $registrationTickets = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT id as 'code', user_name as 'name', reg_id as 'id', ticket_status as 'checkin' FROM $tableName WHERE reg_id = %d",
        absint($eventId)
      )
    );
    return $registrationTickets;
  }
  return false;
}

function getTicket($ticketId = null)
{
  global $wpdb;
  $tableName = $wpdb->prefix . "eventer_registration_tickets";
  if (!empty($ticketId)) {
    $ticket = $wpdb->get_row(
      $wpdb->prepare("SELECT * FROM $tableName WHERE id = %d", absint($ticketId)),
      OBJECT
    );
    return $ticket;
  }
  return false;
}

function addRegistrationMeta($data)
{
  global $wpdb;
  $table_name = $wpdb->prefix . "eventer_registration_meta";
  $wpdb->query(
    $wpdb->prepare(
      "INSERT INTO $table_name
      ( reg_id, 	meta_key, meta_value)
      VALUES ( %d, %s, %s )",
      array($data['reg_id'], $data['meta_key'], $data['meta_value'])
    )
  );
  return $wpdb->insert_id;
}

function updateRegistrationMeta($regId, $metaKey, $metaValue)
{
  global $wpdb;
  $tableName = $wpdb->prefix . "eventer_registration_meta";
  $wpdb->update($tableName, ['meta_value' => $metaValue], array('reg_id' => $regId, 'meta_key' => $metaKey), array('%s'), array('%d', '%s'));
}

function getRegistrationMeta($regId, $metaKey)
{
  global $wpdb;
  $tableName = $wpdb->prefix . "eventer_registration_meta";
  if (!empty($regId)) {
    $meta = $wpdb->get_row(
      $wpdb->prepare(
        "SELECT * FROM $tableName WHERE reg_id = %d AND meta_key = %s",
        absint($regId),
        sanitize_key($metaKey)
      )
    );
    if (!empty($meta)) {
      return $meta->meta_value;
    }
  }
  return false;
}

function addTicketMeta($data)
{
  global $wpdb;
  $table_name = $wpdb->prefix . "eventer_ticket_meta";
  $wpdb->query(
    $wpdb->prepare(
      "INSERT INTO $table_name
      ( ticket_id, 	meta_key, meta_value)
      VALUES ( %d, %s, %s )",
      array($data['ticket_id'], $data['meta_key'], $data['meta_value'])
    )
  );
  return $wpdb->insert_id;
}

function getTicketMeta($regId, $metaKey)
{
  global $wpdb;
  $tableName = $wpdb->prefix . "eventer_ticket_meta";
  if (!empty($regId)) {
    $meta = $wpdb->get_row(
      $wpdb->prepare(
        "SELECT * FROM $tableName WHERE ticket_id = %d AND meta_key = %s",
        absint($regId),
        sanitize_key($metaKey)
      )
    );
    if (!empty($meta)) {
      return $meta->meta_value;
    }
  }
  return false;
}

function updateTicketMeta($ticketId, $metaKey, $metaValue)
{
  global $wpdb;
  $tableName = $wpdb->prefix . "eventer_ticket_meta";
  if (!empty(getTicketMeta($ticketId, $metaKey))) {
    $wpdb->update($tableName, ['meta_value' => $metaValue], array('ticket_id' => $ticketId, 'meta_key' => $metaKey), array('%s'), array('%d', '%s'));
  } else {
    addTicketMeta(['ticket_id' => $ticketId, 'meta_key' => $metaKey, 'meta_value' => $metaValue]);
  }
}
