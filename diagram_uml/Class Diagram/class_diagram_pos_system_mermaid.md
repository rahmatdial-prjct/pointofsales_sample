classDiagram
  direction TB

  %% ===== PACKAGE: Core System =====
  class User {
    -id: int
    -name: String
    -email: String
    -password: String
    -role: String
    -is_active: boolean
    -branch_id: int
    -email_verified_at: DateTime
    -remember_token: String
    -created_at: DateTime
    -updated_at: DateTime
    +isDirector() boolean
    +isManager() boolean
    +isEmployee() boolean
    +isActive() boolean
    +canManageUsers() boolean
    +canManageProducts() boolean
    +canManageStock() boolean
    +canProcessTransactions() boolean
    +canProcessReturns() boolean
    +canViewReports() boolean
    +scopeActive(query) Query
    +scopeByRole(query, role) Query
    +scopeByBranch(query, branchId) Query
  }

  class Branch {
    -id: int
    -name: String
    -address: String
    -operational_area: String
    -phone: String
    -email: String
    -is_active: boolean
    -created_at: DateTime
    -updated_at: DateTime
    -deleted_at: DateTime
    +users() HasMany
    +transactions() HasMany
    +returns() HasMany
    +products() HasMany
  }

  %% ===== PACKAGE: Product Management =====
  class Category {
    -id: int
    -name: String
    -slug: String
    -description: String
    -is_active: boolean
    -created_at: DateTime
    -updated_at: DateTime
    -deleted_at: DateTime
    +products() HasMany
    +boot() void
  }

  class Product {
    -id: int
    -name: String
    -sku: String
    -category_id: int
    -description: String
    -image: String
    -base_price: decimal
    -stock: int
    -is_active: boolean
    -branch_id: int
    -created_at: DateTime
    -updated_at: DateTime
    -deleted_at: DateTime
    +category() BelongsTo
    +branch() BelongsTo
    +transactionItems() HasMany
    +returnItems() HasMany
    +scopeActive(query) Query
  }

  %% ===== PACKAGE: Sales Transactions =====
  class Transaction {
    -id: int
    -invoice_number: String
    -branch_id: int
    -user_id: int
    -employee_id: int
    -customer_name: String
    -subtotal: decimal
    -discount_amount: decimal
    -total_amount: decimal
    -payment_method: String
    -status: String
    -notes: String
    -created_at: DateTime
    -updated_at: DateTime
    -deleted_at: DateTime
    +items() HasMany
    +branch() BelongsTo
    +employee() BelongsTo
    +user() BelongsTo
  }

  class TransactionItem {
    -id: int
    -transaction_id: int
    -product_id: int
    -quantity: int
    -price: decimal
    -discount_percentage: decimal
    -discount_amount: decimal
    -subtotal: decimal
    -created_at: DateTime
    -updated_at: DateTime
    +transaction() BelongsTo
    +product() BelongsTo
    +calculateSubtotal() void
  }

  %% ===== PACKAGE: Return Management =====
  class ReturnTransaction {
    -id: int
    -return_number: String
    -branch_id: int
    -user_id: int
    -transaction_id: int
    -reason: String
    -total: decimal
    -status: String
    -approved_by: int
    -approved_at: DateTime
    -notes: String
    -created_at: DateTime
    -updated_at: DateTime
    -deleted_at: DateTime
    +returnItems() HasMany
    +employee() BelongsTo
    +user() BelongsTo
    +branch() BelongsTo
    +originalTransaction() BelongsTo
  }

  class ReturnItem {
    -id: int
    -return_transaction_id: int
    -product_id: int
    -quantity: int
    -price: decimal
    -subtotal: decimal
    -reason: String
    -condition: String
    -created_at: DateTime
    -updated_at: DateTime
    +returnTransaction() BelongsTo
    +product() BelongsTo
    +calculateSubtotal() void
  }

  %% ===== RELATIONSHIPS =====
  Branch "1" --> "0..*" User : has many
  User "0..*" --> "1" Branch : belongs to

  Category "1" --> "0..*" Product : has many
  Product "0..*" --> "1" Category : belongs to
  Branch "1" --> "0..*" Product : has many
  Product "0..*" --> "1" Branch : belongs to

  User "1" --> "0..*" Transaction : processes
  Branch "1" --> "0..*" Transaction : has many
  Transaction "1" --> "0..*" TransactionItem : contains
  Product "1" --> "0..*" TransactionItem : sold in

  User "1" --> "0..*" ReturnTransaction : processes
  Branch "1" --> "0..*" ReturnTransaction : has many
  Transaction "1" --> "0..*" ReturnTransaction : returned from
  ReturnTransaction "1" --> "0..*" ReturnItem : contains
  Product "1" --> "0..*" ReturnItem : returned as