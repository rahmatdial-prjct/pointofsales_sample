Table users {
  id int [pk, increment]
  name varchar(255) [not null]
  email varchar(255) [unique, not null]
  password varchar(255) [not null]
  role enum('director', 'manager', 'employee') [not null]
  is_active boolean [default: true]
  branch_id int [ref: > branches.id]
  email_verified_at timestamp
  remember_token varchar(100)
  created_at timestamp [default: `now()`]
  updated_at timestamp [default: `now()`]

  Note: 'Base class untuk semua role: Direktur, Manajer, Pegawai'
}

Table branches {
  id int [pk, increment]
  name varchar(255) [not null]
  address text
  operational_area varchar(255)
  phone varchar(20)
  email varchar(255)
  is_active boolean [default: true]
  created_at timestamp [default: `now()`]
  updated_at timestamp [default: `now()`]
  deleted_at timestamp
}

Table categories {
  id int [pk, increment]
  name varchar(255) [not null]
  slug varchar(255) [unique, not null]
  description text
  is_active boolean [default: true]
  created_at timestamp [default: `now()`]
  updated_at timestamp [default: `now()`]
  deleted_at timestamp
}

Table products {
  id int [pk, increment]
  name varchar(255) [not null]
  sku varchar(100) [unique, not null]
  category_id int [ref: > categories.id, not null]
  description text
  image varchar(255)
  base_price decimal(12,2) [not null]
  stock int [default: 0]
  is_active boolean [default: true]
  branch_id int [ref: > branches.id, not null]
  created_at timestamp [default: `now()`]
  updated_at timestamp [default: `now()`]
  deleted_at timestamp
}

Table transactions {
  id int [pk, increment]
  invoice_number varchar(255) [unique, not null]
  branch_id int [ref: > branches.id, not null]
  user_id int [ref: > users.id]
  employee_id int [ref: > users.id, not null]
  customer_name varchar(255)
  subtotal decimal(12,2) [not null]
  discount_amount decimal(12,2) [default: 0]
  total_amount decimal(12,2) [not null]
  payment_method enum('cash', 'card', 'transfer') [not null]
  status enum('pending', 'completed', 'cancelled') [default: 'pending']
  notes text
  created_at timestamp [default: `now()`]
  updated_at timestamp [default: `now()`]
  deleted_at timestamp

  Note: 'Transaksi penjualan dengan multiple items'
}

Table transaction_items {
  id int [pk, increment]
  transaction_id int [ref: > transactions.id, not null]
  product_id int [ref: > products.id, not null]
  quantity int [not null]
  price decimal(12,2) [not null]
  discount_percentage decimal(5,2) [default: 0]
  discount_amount decimal(12,2) [default: 0]
  subtotal decimal(12,2) [not null]
  created_at timestamp [default: `now()`]
  updated_at timestamp [default: `now()`]
}

Table return_transactions {
  id int [pk, increment]
  return_number varchar(255) [unique, not null]
  branch_id int [ref: > branches.id, not null]
  user_id int [ref: > users.id, not null]
  transaction_id int [ref: > transactions.id, not null]
  reason text
  total decimal(12,2) [not null]
  status enum('pending', 'approved', 'rejected') [default: 'pending']
  approved_by int [ref: > users.id]
  approved_at timestamp
  notes text
  created_at timestamp [default: `now()`]
  updated_at timestamp [default: `now()`]
  deleted_at timestamp

  Note: 'Transaksi return dengan approval workflow'
}

Table return_items {
  id int [pk, increment]
  return_transaction_id int [ref: > return_transactions.id, not null]
  product_id int [ref: > products.id, not null]
  quantity int [not null]
  price decimal(12,2) [not null]
  subtotal decimal(12,2) [not null]
  reason varchar(255)
  condition enum('damaged', 'defective', 'wrong_item', 'customer_request')
  created_at timestamp [default: `now()`]
  updated_at timestamp [default: `now()`]
}

 