# UML Diagrams - Layered Approach Documentation

## 📋 **Overview**
Dokumentasi ini menjelaskan pendekatan berlapis (layered approach) dalam pembuatan diagram UML untuk sistem POS UMKM baju muslim wanita. Pendekatan ini memisahkan diagram berdasarkan target audience dan level detail teknis.

## 🎯 **Layered Approach Strategy**

### **Business Analysis Layer**
- **Target**: Stakeholder bisnis, pemilik toko, manajer operasional
- **Focus**: Proses bisnis yang dapat diamati dan dipahami tanpa pengetahuan teknis
- **Diagrams**: Use Case Diagrams, Activity Diagrams
- **Language**: Bahasa Indonesia dengan terminologi bisnis retail

### **Technical Implementation Layer**
- **Target**: Developer, system architect, technical team
- **Focus**: Detail implementasi Laravel, komponen sistem, interaksi teknis
- **Diagrams**: Sequence Diagrams, Class Diagrams
- **Language**: Bahasa Indonesia dengan terminologi teknis

## 📁 **File Organization Structure**

```
diagram_uml/
├── Use Case/
│   ├── use_case_diagram_business_layer.puml
│   ├── use_case_diagram_business_layer_mermaid.md
│   └── (existing technical use case diagrams)
├── Activity Diagram/
│   ├── activity_diagram_jual_produk_business.puml
│   ├── activity_diagram_jual_produk_business_mermaid.md
│   ├── activity_diagram_proses_retur_business.puml
│   ├── activity_diagram_proses_retur_business_mermaid.md
│   ├── activity_diagram_kelola_produk_business.puml
│   ├── activity_diagram_kelola_produk_business_mermaid.md
│   └── (existing technical activity diagrams)
├── Sequence Diagram/
│   ├── sequence_diagram_transaction_technical.puml
│   ├── sequence_diagram_transaction_technical_mermaid.md
│   ├── sequence_diagram_return_technical.puml
│   ├── sequence_diagram_return_technical_mermaid.md
│   ├── sequence_diagram_authentication_technical.puml
│   ├── sequence_diagram_authentication_technical_mermaid.md
│   └── (existing sequence diagrams)
├── Class Diagram/
│   ├── class_diagram_pos_system_new.puml
│   ├── class_diagram_pos_system_new.md
│   └── (existing class diagrams)
└── README_Layered_Approach.md
```

## 🏪 **Business Analysis Layer Specifications**

### **Use Case Diagrams**
- **Actors**: Direktur, Manajer, Pegawai (Indonesian names)
- **Use Cases**: Business outcomes yang dapat diamati
- **Examples**: 
  - "Jual Produk Baju Muslim"
  - "Setujui/Tolak Retur Produk"
  - "Kelola Stok Produk Hijab & Gamis"

### **Activity Diagrams**
- **Complexity**: 5-8 main activities maximum
- **Decision Points**: 1-2 per diagram
- **Focus**: Primary happy path only
- **Swimlanes**: Vertical columns per actor
- **Examples**:
  - Jual Produk: Terima pelanggan → Cari produk → Proses pembayaran → Cetak struk
  - Proses Retur: Terima keluhan → Periksa kondisi → Review manajer → Proses pengembalian

### **Business Abstraction Standards**
**Include (User-Observable Actions):**
- "Masukkan nama produk hijab"
- "Pilih kategori jilbab"
- "Konfirmasi pembayaran tunai"
- "Scan barcode produk"
- "Setujui retur gamis rusak"

**Exclude (Technical Details):**
- Laravel controllers, models, routes
- Database queries, API calls
- Validation rules, middleware
- Blade templates, HTTP requests

## 🔧 **Technical Implementation Layer Specifications**

### **Sequence Diagrams**
- **Structure**: Follow uploaded reference image format exactly
- **Participants**: Laravel components (Controllers, Models, Database, Views)
- **Messages**: HTTP requests, method calls, database operations
- **Notation**: Proper UML sequence diagram elements
- **Examples**:
  - Transaction: Employee → TransactionController → Models → Database
  - Return: Employee → ReturnController → Manager → Database
  - Auth: User → LoginController → Middleware → Session

### **Class Diagrams**
- **Format**: UML standard with visibility modifiers
- **Attributes**: Private fields with `-` prefix
- **Operations**: Public methods with `+` prefix
- **Relationships**: Proper UML notation arrows
- **Structure**: Three-section format (Name, Attributes, Operations)

### **Technical Detail Standards**
**Include (Implementation Details):**
- Laravel Controller methods
- Eloquent Model relationships
- Database table operations
- HTTP request/response cycles
- Middleware authentication
- Session management

## 🎨 **Consistency Standards**

### **Language Requirements**
- **Actor Names**: "Direktur", "Manajer", "Pegawai" (never English)
- **Business Terms**: Indonesian retail terminology
- **Technical Terms**: Indonesian with technical accuracy
- **UI Elements**: Consistent with web interface language

### **UML Standards Compliance**
- **Use Case**: Oval shapes, stickman actors
- **Activity**: Swimlane format, proper flow notation
- **Sequence**: Lifelines, activation boxes, message arrows
- **Class**: Three-section structure, visibility modifiers

### **Format Requirements**
- **Dual Format**: Both PlantUML (.puml) and Mermaid (.md)
- **Draw.io Compatibility**: Mermaid syntax compatible with draw.io web
- **Styling**: Consistent color schemes and formatting
- **Documentation**: Comprehensive explanations in Markdown

## 🛍️ **Context Specificity**

### **Muslim Women's Clothing Retail**
- **Product Types**: Hijab, jilbab, gamis, mukena, aksesoris muslim
- **Business Scenarios**: Typical UMKM retail operations
- **Customer Interactions**: Indonesian cultural context
- **Payment Methods**: Tunai, transfer, QRIS (local payment systems)

### **UMKM Business Terminology**
- **Operational Areas**: Use existing 'operational_area' column
- **Revenue Calculation**: Net revenue = gross sales - approved returns
- **Return Reasons**: Rusak, cacat, salah ukuran
- **Stock Management**: Real-time inventory tracking

## ✅ **Quality Validation Criteria**

### **Business Layer Validation**
- **Stakeholder Recognition**: Store owners can recognize real processes
- **Non-Technical Understanding**: No technical knowledge required
- **Business Value**: Clear operational benefits
- **Process Accuracy**: Reflects actual retail workflows

### **Technical Layer Validation**
- **Developer Understanding**: Clear Laravel implementation details
- **System Architecture**: Proper component interactions
- **Code Accuracy**: Reflects actual codebase structure
- **Implementation Feasibility**: Technically sound solutions

### **Overall Standards**
- **Draw.io Compatibility**: All Mermaid diagrams render correctly
- **UML Compliance**: Follow established standardization documents
- **File Organization**: Proper folder structure and naming
- **Documentation Quality**: Comprehensive and accurate explanations

## 🔄 **Reverse Engineering Methodology**
All diagrams document business processes and technical implementations discovered through analysis of the existing 96% complete Laravel POS system codebase. They represent what the system currently enables, not designed requirements.

## 📊 **Usage Guidelines**

### **For Business Stakeholders**
- Use Business Analysis Layer diagrams for:
  - Process understanding and validation
  - Staff training and onboarding
  - Operational procedure documentation
  - Business process improvement discussions

### **For Technical Teams**
- Use Technical Implementation Layer diagrams for:
  - System architecture understanding
  - Code maintenance and debugging
  - Feature development planning
  - Technical documentation and handover

### **For Project Management**
- Use both layers for:
  - Requirement validation
  - Progress tracking
  - Stakeholder communication
  - System documentation completeness
