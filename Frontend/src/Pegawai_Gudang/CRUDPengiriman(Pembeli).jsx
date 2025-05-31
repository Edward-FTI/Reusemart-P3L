import React, { useEffect, useState } from "react";
// import { toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { toast } from "sonner";
import Select from "react-select";
import jsPDF from "jspdf";
import { Link } from "react-router-dom";
import {
  CreatePengambilan,
  GetPengambilan,
  UpdatePengambilan,
  DeletePengambilan,
  GetPengambilanById
} from "../Api/ApiPengambilan"
import {
    GetAllBarang,
} from "../Api/apiBarang";
import { GetAllKategori } from "../Api/apiKategori";
import { GetAllPenitip } from "../Api/apiPenitip";
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

const CRUDBarangTitipan = () => {
    const [barangList, setBarangList] = useState([]);
    const [transaksiPenjualanList, setTransaksiPenjualanList] = useState([]);
    const [selectedTransaksi, setSelectedTransaksi] = useState(null);

    const [kategoriList, setKategoriList] = useState([]);
    const [penitipList, setPenitipList] = useState([]);

    const [isEdit, setIsEdit] = useState(false);

    const [searchTerm, setSearchTerm] = useState('');
    const [filteredBarangList, setFilteredBarangList] = useState([]);
    const [selectedBarang, setSelectedBarang] = useState(null);

    const handleShowDetail = (barang) => {
        setSelectedBarang(barang);
        const modal = new window.bootstrap.Modal(document.getElementById('detailModal'));
        modal.show();
    };


    const [form, setForm] = useState({
        id: '',
        id_penitip: '',
        id_kategori: '',
        tgl_penitipan: '',
        nama_barang: '',
        harga_barang: '',
        berat_barang: '',
        deskripsi: '',
        status_garansi: '',
        status_barang: '',
        gambar: '',
        gambar_dua: ''
    })

    const fetchBarang = async () => {
        try {
            const data = await GetAllBarang();
            setBarangList(data);
            setFilteredBarangList(data); // Awalnya tampilkan semua
        } catch (error) {
            toast.error("Gagal mengambil data barang");
        }
    };

    const handleSearch = () => {
        const filtered = barangList.filter(b =>
            b.nama_barang.toLowerCase().includes(searchTerm.toLowerCase()) ||
            b.penitip?.nama_penitip?.toLowerCase().includes(searchTerm.toLowerCase()) ||
            b.kategori_barang?.nama_kategori?.toLowerCase().includes(searchTerm.toLowerCase()) ||
            b.status_barang.toLowerCase().includes(searchTerm.toLowerCase()) ||
            b.tgl_penitipan.toLowerCase().includes(searchTerm.toLowerCase()) ||
            b.masa_penitipan.toLowerCase().includes(searchTerm.toLowerCase())
        );
        setFilteredBarangList(filtered);
    };

    const fetchKategori = async () => {
        try {
            const data = await GetAllKategori();
            setKategoriList(data);
        }
        catch (error) {
            toast.error('Gagal mengambil data kategori')
        }
    }

    const fetchPenitip = async () => {
        try {
            const data = await GetAllPenitip();
            setPenitipList(data);
        }
        catch (error) {
            toast.error('Gagal mengambil data penitip');
        }
    }

    useEffect(() => {
        fetchBarang();
        fetchKategori();
        fetchPenitip();
    }, []);

    const handleChange = (e) => {
        const { name, value, files } = e.target;
        setForm({
            ...form,
            [name]: files ? files[0] : value
        });
    };

    const resetForm = () => {
        setForm({
            id: '',
            id_penitip: '',
            id_kategori: '',
            tgl_penitipan: '',
            nama_barang: '',
            harga_barang: '',
            berat_barang: '',
            deskripsi: '',
            status_garansi: '',
            status_barang: '',
            gambar: '',
            gambar_dua: ''
        });
        setIsEdit(false);
    }

    const handleDownloadNota = (orderData) => {
    const doc = new jsPDF();

    // Set font for the entire document if needed, or per text call
    doc.setFont("times", "normal"); // Default font

    let y = 15; // Starting Y position

    // ReUse Mart Header
    doc.setFontSize(14);
    doc.setFont("times", "bold");
    doc.text("ReUse Mart", 10, y);
    y += 5;
    doc.setFontSize(10);
    doc.setFont("times", "normal");
    doc.text("Jl. Green Eco Park No. 456 Yogyakarta", 10, y);
    y += 10;

    // Nota Info (aligning content as seen in the image)
    doc.setFontSize(10);
    doc.text(`No Nota           : ${orderData.nomor_nota || '24.02.101'}`, 10, y);
    y += 5;
    doc.text(`Tanggal pesan     : ${orderData.tanggal_pesan || '15/2/2025'} ${orderData.jam_pesan || '18:50'}`, 10, y);
    y += 5;
    doc.text(`Lunas pada        : ${orderData.tanggal_lunas || '15/2/2024'} ${orderData.jam_lunas || '19:01'}`, 10, y);
    y += 5;
    doc.text(`Tanggal ambil     : ${orderData.tanggal_ambil || '16/2/2024'}`, 10, y);
    y += 10;

    // Pembeli Info
    doc.setFontSize(10);
    doc.setFont("times", "bold");
    doc.text(`Pembeli : ${orderData.pembeli.email || 'cath123@gmail.com'} / ${orderData.pembeli.nama || 'Catherine'}`, 10, y);
    y += 5;
    doc.setFont("times", "normal");
    doc.text(`${orderData.pembeli.alamat || 'Perumahan Griya Persada XII/20'}`, 10, y);
    y += 5;
    doc.text(`${orderData.pembeli.kota || 'Caturtunggal, Depok, Sleman'}`, 10, y);
    y += 5;
    doc.text(`Delivery: - (${orderData.delivery_method || 'diambil sendiri'})`, 10, y);
    y += 10;

    // Items List
    doc.setFontSize(10);
    orderData.items.forEach(item => {
        doc.text(`${item.nama_barang}`, 10, y);
        doc.text(`${item.harga?.toLocaleString('id-ID')}`, 100, y, { align: 'right' }); // Align price to the right
        y += 5;
    });
    y += 5; // Extra spacing after items

    // Summary
    doc.line(10, y, 10 + 100, y); // Line separator for total
    y += 5;
    doc.setFont("times", "normal");
    doc.text(`Total`, 10, y);
    doc.text(`${orderData.total_items_price?.toLocaleString('id-ID')}`, 100, y, { align: 'right' });
    y += 5;
    doc.text(`Ongkos Kirim`, 10, y);
    doc.text(`${orderData.ongkos_kirim?.toLocaleString('id-ID')}`, 100, y, { align: 'right' });
    y += 5;
    doc.setFont("times", "bold"); // Total is bold
    doc.text(`Total`, 10, y);
    doc.text(`${orderData.total_before_discount?.toLocaleString('id-ID')}`, 100, y, { align: 'right' });
    y += 5;
    doc.setFont("times", "normal");
    doc.text(`Potongan ${orderData.potongan_poin_value} poin`, 10, y);
    doc.text(`- ${orderData.potongan_poin_amount?.toLocaleString('id-ID')}`, 100, y, { align: 'right' });
    y += 5;
    doc.setFontSize(12); // Final total slightly larger
    doc.setFont("times", "bold");
    doc.text(`Total`, 10, y);
    doc.text(`${orderData.final_total?.toLocaleString('id-ID')}`, 100, y, { align: 'right' });
    y += 10;

    // Points Info
    doc.setFontSize(10);
    doc.setFont("times", "normal");
    doc.text(`Poin dari pesanan ini: ${orderData.poin_didapat}`, 10, y);
    y += 5;
    doc.text(`Total poin customer: ${orderData.total_poin_customer}`, 10, y);
    y += 10;

    // Signatures
    doc.text(`QC oleh: ${orderData.qc_oleh || 'Farida (P18)'}`, 10, y);
    y += 10; // Space for signature
    doc.text(`Diambil oleh:`, 10, y);
    y += 15; // Space for signature

    doc.text(`(...................................)`, 10, y);
    y += 5;
    doc.text(`Tanggal: ${new Date().toLocaleDateString('id-ID')}`, 10, y);

    // No outer rectangle in the new image, so removed doc.rect.

    doc.save(`Nota_Penjualan_${orderData.nomor_nota || '24.02.101'}.pdf`);
};

// --- Dummy Data Example (to simulate what you would pass to the function) ---
// You would replace this with actual order data from your API or state
const dummyOrderData = {
    nomor_nota: '24.02.101',
    tanggal_pesan: '15/2/2025',
    jam_pesan: '18:50',
    tanggal_lunas: '15/2/2024', // Note the year discrepancy in image, kept as is
    jam_lunas: '19:01',
    tanggal_ambil: '16/2/2024',
    pembeli: {
        email: 'cath123@gmail.com',
        nama: 'Catherine',
        alamat: 'Perumahan Griya Persada XII/20',
        kota: 'Caturtunggal, Depok, Sleman',
    },
    delivery_method: 'diambil sendiri',
    items: [
        { nama_barang: 'Kompor tanam 3 tungku', harga: 2000000 },
        { nama_barang: 'Hair Dryer Ion', harga: 500000 },
    ],
    total_items_price: 2500000,
    ongkos_kirim: 0,
    total_before_discount: 2500000,
    potongan_poin_value: 200,
    potongan_poin_amount: 20000,
    final_total: 2480000,
    poin_didapat: 297,
    total_poin_customer: 300,
    qc_oleh: 'Farida (P18)',
    // 'diambil_oleh' is typically filled manually, so left blank
};


    return (
        <div className="container mt-5 bg-white p-4 rounded shadow">
    {/* Header: Judul & Tombol Tambah */}
    <div className="d-flex justify-content-between align-items-center mb-4">
      <h2 className="mb-0">Data Pengiriman Pembeli</h2>
      <button
        className="btn btn-success"
        onClick={() => {
          resetForm();
          const modal = new window.bootstrap.Modal(document.getElementById("formModal"));
          modal.show();
        }}
      >
        Tambah Data
      </button>
    </div>

    {/* Navigasi Penitip & Pembeli + Pencarian */}
    <div className="row mb-4">
      <div className="col-md-6 d-flex gap-2">
        <Link to="/gudang/pengiriman/penitip" className="btn btn-outline-primary">
          Halaman Penitip
        </Link>
        <Link to="/gudang/pengiriman/pembeli" className="btn btn-outline-secondary">
          Halaman Pembeli
        </Link>
      </div>
      <div className="col-md-6">
        <form
          className="d-flex"
          onSubmit={(e) => {
            e.preventDefault();
            handleSearch();
          }}
        >
          <input
            type="search"
            name="cari"
            className="form-control me-2"
            placeholder="Cari barang..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
          />
          <button className="btn btn-outline-primary" type="submit">
            Cari
          </button>
        </form>
      </div>
    </div>

    {/* Tabel Barang */}
    <table className="table table-bordered table-hover">
      <thead className="table-light">
        <tr>
          <th>No</th>
          <th>Nama Penitip</th>
          <th>Kategori Barang</th>
          <th>Tanggal Penitipan</th>
          <th>Masa Penitipan</th>
          <th>Nama Barang</th>
          <th>Harga Barang</th>
          <th>Deskripsi</th>
          <th>Status Barang</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        {filteredBarangList.length > 0 ? (
          filteredBarangList.map((b, index) => (
            <tr key={b.id}>
              <td>{index + 1}</td>
              <td>{b.penitip.nama_penitip}</td>
              <td>{b.kategori_barang.nama_kategori || "Kategori tidak ditemukan"}</td>
              <td>{b.tgl_penitipan}</td>
              <td>{b.masa_penitipan}</td>
              <td>{b.nama_barang}</td>
              <td>{b.harga_barang}</td>
              <td>{b.deskripsi}</td>
              <td>{b.status_barang}</td>
              <td>
                <div className="d-flex flex-column">
                  <button className="btn btn-sm btn-primary mb-1" onClick={() => handleShowDetail(b)}>
                    Detail
                  </button>
                <button
  className="btn btn-sm btn-success"
  onClick={() => {
      const orderData = {
        nomor_nota: `INV-${b.id}`,
        tanggal_pesan: b.tgl_penitipan,
        jam_pesan: '09:00',
        tanggal_lunas: b.tgl_penitipan,
        jam_lunas: '10:00',
        tanggal_ambil: b.tgl_penitipan,
        pembeli: {
            email: 'unknown@email.com',
            nama: b.penitip.nama_penitip,
            alamat: 'Alamat belum tersedia',
            kota: 'Kota belum tersedia',
        },
        delivery_method: 'diambil sendiri',
        items: [
            {
                nama_barang: b.nama_barang,
                harga: parseInt(b.harga_barang),
            },
        ],
        total_items_price: parseInt(b.harga_barang),
        ongkos_kirim: 0,
        total_before_discount: parseInt(b.harga_barang),
        potongan_poin_value: 0,
        potongan_poin_amount: 0,
        final_total: parseInt(b.harga_barang),
        poin_didapat: Math.floor(parseInt(b.harga_barang) / 10000),
        total_poin_customer: 0,
        qc_oleh: 'Farida (P18)',
      };
      handleDownloadNota(orderData);
  }}
>
  Unduh Nota Item
</button>

                </div>
              </td>
            </tr>
          ))
        ) : (
          <tr>
            <td colSpan="10" className="text-center fs-5">
              Belum ada data barang
            </td>
          </tr>
        )}
      </tbody>
    </table>


            {/* Modal untuk detail barang */}
            <div className="modal fade" id="detailModal" tabIndex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                <div className="modal-dialog modal-lg">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h5 className="modal-title" id="detailModalLabel">Detail Barang</h5>
                            <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div className="modal-body">
                            {selectedBarang && (
                                <div className="row">
                                    <div className="col-md-6">
                                        <div id="carouselGambar" className="carousel slide" data-bs-ride="carousel">
                                            <div className="carousel-inner">
                                                <div className="carousel-item active">
                                                    <img
                                                        src={`http://localhost:8000/${selectedBarang.gambar}`}
                                                        className="d-block w-100 rounded"
                                                        alt="Gambar 1"
                                                        style={{ maxHeight: "300px", objectFit: "contain" }}
                                                    />
                                                </div>
                                                {selectedBarang.gambar_dua && (
                                                    <div className="carousel-item">
                                                        <img
                                                            src={`http://localhost:8000/${selectedBarang.gambar_dua}`}
                                                            className="d-block w-100 rounded"
                                                            alt="Gambar 2"
                                                            style={{ maxHeight: "300px", objectFit: "contain" }}
                                                        />
                                                    </div>
                                                )}
                                            </div>
                                            <button className="carousel-control-prev" type="button" data-bs-target="#carouselGambar" data-bs-slide="prev">
                                                <span className="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span className="visually-hidden">Previous</span>
                                            </button>
                                            <button className="carousel-control-next" type="button" data-bs-target="#carouselGambar" data-bs-slide="next">
                                                <span className="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span className="visually-hidden">Next</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div className="col-md-6">
                                        <h5>{selectedBarang.nama_barang}</h5>
                                        {/* <p><strong>Status Barang:</strong> {selectedBarang.status_barang}</p> */}
                                        <p><strong>Status Garansi:</strong> {selectedBarang.status_garansi}</p>
                                        <p><strong>Penitip:</strong> {selectedBarang.penitip.nama_penitip}</p>
                                        <p><strong>Kategori:</strong> {selectedBarang.kategori_barang.nama_kategori}</p>
                                        <p><strong>Berat Barang:</strong> {selectedBarang.berat_barang} kg</p>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </div >
    );
};

export default CRUDBarangTitipan;
