# Use Case Diagram - Draw.io Manual (XML Format)

## 📋 Informasi File

**File**: `use_case_diagram_pos_system_drawio.xml`  
**Format**: Draw.io XML dengan notasi UML standar  
**Berdasarkan**: Standarisasi UML Modul 1 APSI yang telah dibuat  
**Aktor**: 3 aktor utama (Direktur, Manajer, Pegawai)  

---

## 🎯 Notasi yang Digunakan (Sesuai Standar UML)

### ✅ **Actor (Stickman)**
```xml
<mxCell id="direktur" value="Direktur" 
  style="shape=umlActor;verticalLabelPosition=bottom;verticalAlign=top;html=1;outlineConnect=0;strokeWidth=2;fillColor=#e1f5fe;strokeColor=#01579b;" 
  vertex="1" parent="1">
  <mxGeometry x="50" y="200" width="30" height="60" as="geometry" />
</mxCell>
```

### ✅ **Use Case (Oval)**
```xml
<mxCell id="login" value="Login" 
  style="ellipse;whiteSpace=wrap;html=1;strokeWidth=2;fillColor=#f3e5f5;strokeColor=#4a148c;" 
  vertex="1" parent="1">
  <mxGeometry x="300" y="150" width="100" height="50" as="geometry" />
</mxCell>
```

### ✅ **System Boundary (Kotak Besar)**
```xml
<mxCell id="system-boundary" value="Sistem POS UMKM" 
  style="rounded=0;whiteSpace=wrap;html=1;strokeWidth=2;fillColor=#e8f5e8;strokeColor=#2e7d32;fontSize=16;fontStyle=1;verticalAlign=top;spacingTop=10;" 
  vertex="1" parent="1">
  <mxGeometry x="200" y="100" width="800" height="600" as="geometry" />
</mxCell>
```

### ✅ **Association (Garis Solid)**
```xml
<mxCell id="direktur-login" value="" 
  style="endArrow=none;html=1;strokeWidth=2;" 
  edge="1" parent="1" source="direktur" target="login">
  <mxGeometry width="50" height="50" relative="1" as="geometry">
    <mxPoint x="100" y="230" as="sourcePoint" />
    <mxPoint x="300" y="175" as="targetPoint" />
  </mxGeometry>
</mxCell>
```

### ✅ **Include Relationship (Panah + Label)**
```xml
<mxCell id="login-include-validasi" value="&lt;&lt;include&gt;&gt;" 
  style="endArrow=open;endSize=12;dashed=1;html=1;strokeWidth=2;" 
  edge="1" parent="1" source="login" target="validasi-akses">
  <mxGeometry width="160" relative="1" as="geometry">
    <mxPoint x="400" y="175" as="sourcePoint" />
    <mxPoint x="450" y="175" as="targetPoint" />
  </mxGeometry>
</mxCell>
```

### ✅ **Extend Relationship (Panah + Label)**
```xml
<mxCell id="struk-extend-pdf" value="&lt;&lt;extend&gt;&gt;" 
  style="endArrow=open;endSize=12;dashed=1;html=1;strokeWidth=2;" 
  edge="1" parent="1" source="cetak-struk" target="generate-pdf">
  <mxGeometry width="160" relative="1" as="geometry">
    <mxPoint x="610" y="495" as="sourcePoint" />
    <mxPoint x="640" y="495" as="targetPoint" />
  </mxGeometry>
</mxCell>
```

---

## 🚀 Cara Menggunakan File XML

### **Method 1: Import Langsung**
1. Buka [draw.io](https://app.diagrams.net/)
2. File → Open from → Device
3. Pilih file `use_case_diagram_pos_system_drawio.xml`
4. Diagram akan ter-load dengan notasi UML yang benar

### **Method 2: Copy-Paste XML**
1. Buka draw.io
2. File → Import from → XML
3. Copy-paste seluruh isi file XML
4. Klik Import

### **Method 3: Drag & Drop**
1. Buka draw.io di browser
2. Drag file XML ke area kerja
3. Diagram akan otomatis ter-import

---

## 📊 Elemen Diagram yang Tersedia

### 👥 **Actors (3 Stickman)**
- **Direktur** - Posisi kiri atas
- **Manajer** - Posisi kiri tengah  
- **Pegawai** - Posisi kiri bawah

### 🎯 **Use Cases (16 Oval)**
| Kategori | Use Cases |
|----------|-----------|
| **Authentication** | Login, Validasi Akses Multi-Role |
| **Direktur** | Kelola Cabang, Kelola User, Laporan Terintegrasi |
| **Manajer** | Kelola Produk, Kelola Kategori, Kelola Pegawai, Proses Return, Dashboard Cabang |
| **Pegawai** | Proses Transaksi, Update Stok Otomatis, Cetak Struk, Generate PDF Invoice |
| **Common** | Cari dan Filter Data, Export Data |

### 🔗 **Relationships**
- **Association**: 15 garis solid (aktor ke use case)
- **Include**: 2 panah dashed (Login → Validasi, Transaksi → Update Stok)
- **Extend**: 1 panah dashed (Cetak Struk → Generate PDF)

---

## 🎨 Color Scheme

### **Actors (Stickman)**
- **Fill**: `#e1f5fe` (Light Blue)
- **Stroke**: `#01579b` (Dark Blue)

### **Use Cases (Oval)**
- **Fill**: `#f3e5f5` (Light Purple)
- **Stroke**: `#4a148c` (Dark Purple)

### **System Boundary**
- **Fill**: `#e8f5e8` (Light Green)
- **Stroke**: `#2e7d32` (Dark Green)

### **Lines**
- **Association**: Solid black, strokeWidth=2
- **Include/Extend**: Dashed black, strokeWidth=2

---

## ✅ Kesesuaian dengan Standar UML

### **Notasi yang Benar:**
- ✅ **Actor**: `shape=umlActor` (Stickman representation)
- ✅ **Use Case**: `ellipse` (Oval shape)
- ✅ **Association**: `endArrow=none` (Garis solid)
- ✅ **System Boundary**: `rounded=0;whiteSpace=wrap` (Kotak besar)
- ✅ **Include**: `endArrow=open;dashed=1` + `<<include>>` label
- ✅ **Extend**: `endArrow=open;dashed=1` + `<<extend>>` label

### **Konsistensi:**
- ✅ **Penamaan**: Direktur, Manajer, Pegawai (sesuai UI sistem)
- ✅ **Bahasa**: Indonesia (konsisten dengan implementasi)
- ✅ **Layout**: Terorganisir dan mudah dibaca
- ✅ **Reverse Engineering**: Berdasarkan fitur yang ada

---

## 🔧 Customization

Jika ingin memodifikasi diagram:

### **Menambah Use Case:**
```xml
<mxCell id="new-usecase" value="Nama Use Case" 
  style="ellipse;whiteSpace=wrap;html=1;strokeWidth=2;fillColor=#f3e5f5;strokeColor=#4a148c;" 
  vertex="1" parent="1">
  <mxGeometry x="X" y="Y" width="100" height="50" as="geometry" />
</mxCell>
```

### **Menambah Association:**
```xml
<mxCell id="actor-usecase" value="" 
  style="endArrow=none;html=1;strokeWidth=2;" 
  edge="1" parent="1" source="actor-id" target="usecase-id">
  <mxGeometry width="50" height="50" relative="1" as="geometry">
    <mxPoint x="X1" y="Y1" as="sourcePoint" />
    <mxPoint x="X2" y="Y2" as="targetPoint" />
  </mxGeometry>
</mxCell>
```

---

*File XML ini dibuat dengan notasi UML standar yang sesuai dengan standarisasi yang telah ditetapkan, siap untuk digunakan di draw.io.*
