# New Simplified Sequence Diagrams - Sistem POS UMKM

## 📋 **Daftar Sequence Diagram Baru**

Folder ini berisi 5 Sequence Diagram yang disederhanakan untuk sistem POS UMKM dengan format **1 Actor + 3 Objects**, masing-masing tersedia dalam 2 format:

### 1. **Proses Transaksi**
- **PlantUML**: `sequence_diagram_transaksi.puml`
- **Mermaid**: `sequence_diagram_transaksi_mermaid.md`
- **Participants**: 👤 Pegawai | 🖥️ UI | 🌐 Controller | 🗄️ Database
- **Fokus**: Alur transaksi penjualan dari input hingga struk

### 2. **Proses Retur**
- **PlantUML**: `sequence_diagram_retur.puml`
- **Mermaid**: `sequence_diagram_retur_mermaid.md`
- **Participants**: 👨‍💼 Manajer | 🖥️ UI | 🌐 Controller | 🗄️ Database
- **Fokus**: Alur persetujuan retur produk

### 3. **Manajemen Stok**
- **PlantUML**: `sequence_diagram_stok.puml`
- **Mermaid**: `sequence_diagram_stok_mermaid.md`
- **Participants**: 👨‍💼 Manajer | 🖥️ UI | 🌐 Controller | 🗄️ Database
- **Fokus**: Alur kelola produk dan update stok

### 4. **Login & Authentication**
- **PlantUML**: `sequence_diagram_login.puml`
- **Mermaid**: `sequence_diagram_login_mermaid.md`
- **Participants**: 👤 User | 🖥️ UI | 🌐 Controller | 🗄️ Database
- **Fokus**: Alur login dan validasi kredensial

### 5. **Generate Laporan**
- **PlantUML**: `sequence_diagram_laporan.puml`
- **Mermaid**: `sequence_diagram_laporan_mermaid.md`
- **Participants**: 👤 Direktur | 🖥️ UI | 🌐 Controller | 🗄️ Database
- **Fokus**: Alur generate laporan dengan role-based access

## 🎯 **Standarisasi Sequence Diagram Baru**

### **Format Struktur**
✅ **Exactly 1 Actor + 3 Objects**:
- 1 Actor: User yang melakukan aksi (Direktur/Manajer/Pegawai)
- 3 Objects: UI, Controller, Database

### **UML Notation Standards**
✅ **Proper UML Elements**:
- **Lifelines**: Vertical dashed lines untuk setiap participant
- **Activation Boxes**: Menggunakan `activate`/`deactivate` statements untuk execution occurrence
- **Message Arrows**: Solid arrows (`->>`) untuk requests, dashed (`-->>`) untuk responses
- **Alt/Else**: Alternative flows untuk conditional logic dengan proper activation handling

### **Technical Implementation**
✅ **Laravel Components Included**:
- **Controllers**: Specific Laravel controllers (TransactionController, etc.)
- **Models**: Eloquent models (Transaction, Product, User, etc.)
- **Routes**: HTTP methods dan endpoints
- **Views**: Blade templates untuk UI
- **Database**: MySQL operations

### **Message Format**
✅ **:order() Notation**:
- Format: `:order(business_action, parameters)` untuk business actions
- Contoh: `:order(mulai_transaksi)`, `:order(tambah_item, kode_produk, jumlah)`
- Tanpa parameter: `:order(akses_menu_produk)`
- Dengan parameter: `:order(proses_pembayaran, total, metode_bayar)`

## 🏪 **Context Specificity**

### **Muslim Women's Clothing Retail**
✅ **Business Context Reflected**:
- Product types: Hijab, jilbab, gamis, mukena
- UMKM business operations
- Indonesian payment methods

### **Indonesian Language Consistency**
✅ **Actor Names**: Direktur, Manajer, Pegawai
✅ **Business Terms**: Indonesian retail terminology
✅ **UI Consistency**: Matches web interface language

## 📁 **File Organization**

### **Dual Format Implementation**
✅ **PlantUML Files**: .puml format untuk technical documentation
✅ **Mermaid Files**: .md format untuk draw.io compatibility
✅ **Consistent Naming**: Clear file naming conventions
✅ **Proper Structure**: Organized dalam dedicated folder

### **Draw.io Compatibility**
✅ **Mermaid Format**: Compatible dengan draw.io web platform
✅ **Standard Syntax**: Menggunakan sequenceDiagram syntax
✅ **Visual Elements**: Icons dan styling untuk professional appearance

## 💻 **Technical Implementation Details**

### **Laravel Framework Integration**
- **Controllers**: HTTP request handling
- **Models**: Database operations via Eloquent ORM
- **Middleware**: Authentication dan authorization
- **Views**: Blade templates untuk user interface
- **Routes**: RESTful API endpoints

### **Database Operations**
- **MySQL**: Primary database untuk data persistence
- **Transactions**: Database transactions untuk data consistency
- **Queries**: SELECT, INSERT, UPDATE operations
- **Relationships**: Foreign key relationships antar tables

## 🎉 **Deliverables Summary**

### **New Simplified Sequence Diagrams (10 files)**
- 5 PlantUML files (.puml)
- 5 Mermaid files (.md)
- 1 README documentation

### **Key Improvements**
- **Standardized Structure**: 1 actor + 3 objects format
- **Execution Occurrence**: Proper activation boxes dengan `activate`/`deactivate` statements
- **Business Action Format**: :order() notation dengan business action names
- **Error-Free Syntax**: Robust activation handling yang tidak menyebabkan syntax errors
- **UML Compliance**: Proper notation dengan lifelines dan activation boxes
- **Draw.io Ready**: Mermaid format compatible dengan draw.io platform
- **Consistent Syntax**: Standardized message format across all diagrams
