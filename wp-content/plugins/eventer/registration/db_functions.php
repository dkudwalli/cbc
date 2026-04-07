<?php
function addRegistration($data)
{
  print_r($data);
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
  if (empty($regId)) {
    $registrations = $wpdb->get_results("SELECT * FROM $tableName ORDER BY ID DESC LIMIT $offset, $limit", ARRAY_A);
    return $registrations;
  } else {
    $registration = $wpdb->get_row("SELECT * FROM $tableName WHERE $col = $regId", OBJECT);
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
    $registrationTickets = $wpdb->get_results("SELECT * FROM $tableName WHERE $col = $regId ORDER BY ID DESC LIMIT $offset, $limit", OBJECT);
    return $registrationTickets;
  }
  return false;
}

function getRegistrants($eventId = null, $ticketDate = null)
{
  global $wpdb;
  $tableName = $wpdb->prefix . "eventer_registration_tickets";
  if (!empty($eventId) && !empty($ticketDate)) {
    $registrationTickets = $wpdb->get_results("SELECT user_name as 'name', reg_id as 'id', reg_id as 'show', ticket_status as 'checkin' FROM $tableName WHERE `event_id` = $eventId AND `ticket_date` = '$ticketDate' GROUP BY `reg_id`");
    return $registrationTickets;
  } elseif (!empty($eventId)) {
    $registrationTickets = $wpdb->get_results("SELECT id as 'code', user_name as 'name', reg_id as 'id', ticket_status as 'checkin' FROM $tableName WHERE `reg_id` = $eventId");
    return $registrationTickets;
  }
  return false;
}

function getTicket($ticketId = null)
{
  global $wpdb;
  $tableName = $wpdb->prefix . "eventer_registration_tickets";
  if (!empty($ticketId)) {
    $ticket = $wpdb->get_row("SELECT * FROM $tableName WHERE id = $ticketId", OBJECT);
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
    $meta = $wpdb->get_row("SELECT * FROM $tableName WHERE reg_id = $regId AND meta_key = '$metaKey'");
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
    $meta = $wpdb->get_row("SELECT * FROM $tableName WHERE ticket_id = $regId AND meta_key = '$metaKey'");
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