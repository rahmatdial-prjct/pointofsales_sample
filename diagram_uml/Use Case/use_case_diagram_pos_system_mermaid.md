```mermaid
graph TB
    %% Actors
    Direktur{{Direktur}}
    Manajer{{Manajer}}
    Pegawai{{Pegawai}}

    %% System Boundary
    subgraph POS[Sistem POS UMKM]
        Login((Login))
        ValidasiAkses((Validasi Akses<br/>Multi Role))
        LaporanTerintegrasi((Laporan Terintegrasi))
        EksporData((Ekspor Data))
        KelolaCabang((Kelola Cabang))
        KelolaUser((Kelola Pengguna))
        DashboardCabang((Dashboard Cabang))
        ProsesReturn((Proses Return))
        KelolaPegawai((Kelola Pegawai))
        KelolaKategori((Kelola Kategori))
        KelolaProduk((Kelola Produk))
        CetakStruk((Cetak Struk))
        GeneratePDF((Generate PDF Faktur))
        CariFilter((Cari dan Filter Data))
        ProsesTransaksi((Proses Transaksi))
        UpdateStok((Update Stok Otomatis))
    end

    %% Associations berdasarkan PlantUML
    %% Direktur connections
    Direktur --- Login
    Direktur --- KelolaCabang
    Direktur --- KelolaUser
    Direktur --- LaporanTerintegrasi
    Direktur --- EksporData

    %% Manajer connections
    Manajer --- Login
    Manajer --- LaporanTerintegrasi
    Manajer --- KelolaProduk
    Manajer --- KelolaKategori
    Manajer --- KelolaPegawai
    Manajer --- ProsesReturn
    Manajer --- DashboardCabang

    %% Pegawai connections
    Pegawai --- Login
    Pegawai --- ProsesTransaksi
    Pegawai --- CetakStruk
    Pegawai --- CariFilter

    %% Include Relationships
    Login -.->|"<<include>>"| ValidasiAkses
    ProsesTransaksi -.->|"<<include>>"| UpdateStok

    %% Extend Relationships
    CetakStruk -.->|"<<extend>>"| GeneratePDF
