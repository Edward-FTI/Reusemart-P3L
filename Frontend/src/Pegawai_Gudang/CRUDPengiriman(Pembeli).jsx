import React, { useEffect, useState } from "react";
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
    GetPengambilanById,
    GetPengambilanByNama
} from "../Api/apiPengambilan"

import { GetAllKategori } from "../Api/apiKategori";
import { GetAllPenitip } from "../Api/apiPenitip";

import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

const CRUDPengirimanPembeli = () => {
    const [pengambilanList, setPengambilanList] = useState([]);
    const [filteredPengambilanList, setFilteredPengambilanList] = useState([]);
    const [selectedTransaksi, setSelectedTransaksi] = useState(null);

    const [kategoriList, setKategoriList] = useState([]);
    const [penitipList, setPenitipList] = useState([]);

    const [isEdit, setIsEdit] = useState(false);
    const [searchTerm, setSearchTerm] = useState('');

    const [form, setForm] = useState({
        id: '',
        id_pembeli: '',
        total_harga_pembelian: '',
        metode_pengiriman: '',
        alamat_pengiriman: '',
        ongkir: '',
        bukti_pembayaran: '',
        status_pengiriman: '',
        id_pegawai: '',
        status_pembelian: '',
        verifikasi_pembayaran: ''
    });

    const fetchPengambilan = async () => {
        try {
            const data = await GetPengambilan();
            setPengambilanList(data);
            setFilteredPengambilanList(data);
        } catch (error) {
            console.error("Error fetching pengambilan data:", error);
            toast.error("Gagal mengambil data pengambilan. Pastikan Anda sudah login.");
        }
    };

    const handleSearch = async () => {
      const search = searchTerm.toLowerCase()

      const filteredData = pengambilanList.filter((p) => {
          return (
              p.pembeli.nama_pembeli.toLowerCase().includes(search) ||
              p.total_harga_pembelian.toString().includes(search) ||
              p.metode_pengiriman.toLowerCase().includes(search) ||
              p.alamat_pengiriman.toLowerCase().includes(search) ||
              p.ongkir.toString().includes(search) ||
              p.status_pengiriman.toLowerCase().includes(search) ||
              p.status_pembelian.toLowerCase().includes(search) ||
              p.verifikasi_pembayaran.toLowerCase().includes(search) ||
              p.id.toString().includes(search)
          );
      });
      setFilteredPengambilanList(filteredData);
    };

    const fetchKategori = async () => {
        try {
            // const data = await GetAllKategori();
            const data = []; // Dummy data
            setKategoriList(data);
        }
        catch (error) {
            console.error('Error fetching kategori data:', error);
            toast.error('Gagal mengambil data kategori');
        }
    }

    const fetchPenitip = async () => {
        try {
            // const data = await GetAllPenitip();
            const data = []; // Dummy data
            setPenitipList(data);
        }
        catch (error) {
            console.error('Error fetching penitip data:', error);
            toast.error('Gagal mengambil data penitip');
        }
    }

    useEffect(() => {
        fetchPengambilan();
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
            id_pembeli: '',
            total_harga_pembelian: '',
            metode_pengiriman: '',
            alamat_pengiriman: '',
            ongkir: '',
            bukti_pembayaran: '',
            status_pengiriman: '',
            id_pegawai: '',
            status_pembelian: '',
            verifikasi_pembayaran: ''
        });
        setIsEdit(false);
    }

    const handleShowDetail = (transaksi) => {
        setSelectedTransaksi(transaksi);
        const modal = new window.bootstrap.Modal(document.getElementById('detailModal'));
        modal.show();
    };

    const handleDownloadNota = (orderData) => {
        const doc = new jsPDF();

        doc.setFont("times", "normal");

        let y = 15;

        doc.setFontSize(14);
        doc.setFont("times", "bold");
        doc.text("ReUse Mart", 10, y);
        y += 5;
        doc.setFontSize(10);
        doc.setFont("times", "normal");
        doc.text("Jl. Green Eco Park No. 456 Yogyakarta", 10, y);
        y += 10;

        // Generate nomor_nota based on the new rule
        const orderDate = new Date(orderData.created_at); // Assuming created_at exists and is a valid date string/object
        const year = orderDate.getFullYear().toString().slice(-2); // Get last two digits of the year
        const month = (orderDate.getMonth() + 1).toString().padStart(2, '0'); // Months are 0-indexed
        const sequentialNumber = orderData.id; 
        const nomorNota = `${year}.${month}.${sequentialNumber}`;


        doc.setFontSize(10);
        doc.text(`No Nota           : ${nomorNota || 'N/A'}`, 10, y);
        y += 5;
        doc.text(`Tanggal pesan     : ${orderData.tanggal_pesan || 'N/A'} ${orderData.jam_pesan || ''}`, 10, y);
        y += 5;
        doc.text(`Lunas pada        : ${orderData.tanggal_lunas || 'N/A'} ${orderData.jam_lunas || ''}`, 10, y);
        y += 5;
        doc.text(`Tanggal ambil     : ${orderData.metode_pengiriman === 'diambil' ? new Date().toLocaleDateString('id-ID') : 'Akan diatur'}`, 10, y);
        y += 10;

        doc.setFontSize(10);
        doc.setFont("times", "bold");
        doc.text(`Pembeli : ${orderData.pembeli.email || 'N/A'} / ${orderData.pembeli.nama_pembeli || 'N/A'}`, 10, y);
        y += 5;
        doc.setFont("times", "normal");
        doc.text(`${orderData.alamat_pengiriman || 'N/A'}`, 10, y);
        y += 5;
        const cityMatch = orderData.alamat_pengiriman.match(/([^,]+)$/);
        const city = cityMatch ? cityMatch[1].trim() : 'N/A';
        doc.text(`${city}`, 10, y);
        y += 5;
        doc.text(`Delivery: - (${orderData.metode_pengiriman || 'N/A'})`, 10, y);
        y += 10;

        doc.setFontSize(10);
        orderData.detail.forEach(item => { 
            doc.text(`${item.barang.nama_barang}`, 10, y);
            doc.text(`${item.harga_saat_transaksi?.toLocaleString('id-ID')}`, 100, y, { align: 'right' });
            y += 5;
        });
        y += 5;

        doc.line(10, y, 10 + 100, y);
        y += 5;
        doc.setFont("times", "normal");
        doc.text(`Total Harga Barang`, 10, y);
        doc.text(`${orderData.detail.reduce((sum, d) => sum + d.harga_saat_transaksi, 0).toLocaleString('id-ID')}`, 100, y, { align: 'right' });
        y += 5;
        doc.text(`Ongkos Kirim`, 10, y);
        doc.text(`${orderData.ongkir?.toLocaleString('id-ID')}`, 100, y, { align: 'right' });
        y += 5;
        doc.setFont("times", "bold");
        doc.text(`Total Pembelian`, 10, y);
        doc.text(`${(orderData.total_harga_pembelian + orderData.ongkir)?.toLocaleString('id-ID')}`, 100, y, { align: 'right' });
        y += 5;
        doc.setFont("times", "normal");
        doc.text(`Potongan 0 poin`, 10, y); 
        doc.text(`- ${0?.toLocaleString('id-ID')}`, 100, y, { align: 'right' });
        y += 5;
        doc.setFontSize(12);
        doc.setFont("times", "bold");
        doc.text(`Total Akhir`, 10, y);
        doc.text(`${(orderData.total_harga_pembelian + orderData.ongkir)?.toLocaleString('id-ID')}`, 100, y, { align: 'right' });
        y += 10;

        doc.setFontSize(10);
        doc.setFont("times", "normal");
        const poinDidapat = Math.floor((orderData.total_harga_pembelian + orderData.ongkir) / 10000);
        doc.text(`Poin dari pesanan ini: ${poinDidapat}`, 10, y);
        y += 5;
        doc.text(`Total poin customer: ${orderData.pembeli.point || 0}`, 10, y);
        y += 10;

        doc.text(`QC oleh: Pegawai ID ${orderData.id_pegawai || 'N/A'}`, 10, y);
        y += 10;
        doc.text(`Diambil oleh:`, 10, y);
        y += 15;

        doc.text(`(...................................)`, 10, y);
        y += 5;
        doc.text(`Tanggal: ${new Date().toLocaleDateString('id-ID')}`, 10, y);

        doc.save(`Nota_Pengambilan_${nomorNota}.pdf`);
    };

    return (
        <div className="container mt-5 bg-white p-4 rounded shadow">
            <div className="d-flex justify-content-between align-items-center mb-4">
                <h2 className="mb-0">Data Pengiriman Pembeli</h2>
                <button
                    className="btn btn-success"
                    onClick={() => {
                        resetForm();
                        toast.info("Fitur tambah data belum diimplementasikan untuk Pengambilan.");
                    }}
                >
                    Tambah Data
                </button>
            </div>

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
                            placeholder="Cari transaksi..."
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                        />
                        <button className="btn btn-outline-primary" type="submit">
                            Cari
                        </button>
                    </form>
                </div>
            </div>

            <table className="table table-bordered table-hover">
                <thead className="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Pembeli</th>
                        <th>Total Harga Pembelian</th>
                        <th>Metode Pengiriman</th>
                        <th>Alamat Pengiriman</th>
                        <th>Ongkir</th>
                        <th>Status Pengiriman</th>
                        <th>Status Pembelian</th>
                        <th>Verifikasi Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {filteredPengambilanList.length > 0 ? (
                        filteredPengambilanList.map((item, index) => (
                            <tr key={item.id}>
                                <td>{index + 1}</td>
                                <td>{item.pembeli.nama_pembeli}</td>
                                <td>{item.total_harga_pembelian.toLocaleString('id-ID')}</td>
                                <td>{item.metode_pengiriman}</td>
                                <td>{item.alamat_pengiriman}</td>
                                <td>{item.ongkir.toLocaleString('id-ID')}</td>
                                <td>{item.status_pengiriman}</td>
                                <td>{item.status_pembelian}</td>
                                <td>{item.verifikasi_pembayaran}</td>
                                <td>
                                    <div className="d-flex flex-column">
                                        <button className="btn btn-sm btn-primary mb-1" onClick={() => handleShowDetail(item)}>
                                            Detail
                                        </button>
                                        
                                        <button
                                            className="btn btn-sm btn-success"
                                            onClick={() => {
                                                handleDownloadNota(item);
                                            }}
                                        >
                                            Unduh Nota
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        ))
                    ) : (
                        <tr>
                            <td colSpan="10" className="text-center fs-5">
                                Belum ada data pengambilan
                            </td>
                        </tr>
                    )}
                </tbody>
            </table>

            {/* Modal for transaction details */}
            <div className="modal fade" id="detailModal" tabIndex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                <div className="modal-dialog modal-lg">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h5 className="modal-title" id="detailModalLabel">Detail Transaksi Pengambilan</h5>
                            <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div className="modal-body">
                            {selectedTransaksi && (
                                <div className="row">
                                    <div className="col-md-6">
                                        <h6>Informasi Pembelian:</h6>
                                        <p><strong>ID Transaksi:</strong> {selectedTransaksi.id}</p>
                                        <p><strong>Nama Pembeli:</strong> {selectedTransaksi.pembeli.nama_pembeli}</p>
                                        <p><strong>Email Pembeli:</strong> {selectedTransaksi.pembeli.email}</p>
                                        <p><strong>Total Harga Pembelian:</strong> {selectedTransaksi.total_harga_pembelian.toLocaleString('id-ID')}</p>
                                        <p><strong>Metode Pengiriman:</strong> {selectedTransaksi.metode_pengiriman}</p>
                                        <p><strong>Alamat Pengiriman:</strong> {selectedTransaksi.alamat_pengiriman}</p>
                                        <p><strong>Ongkir:</strong> {selectedTransaksi.ongkir.toLocaleString('id-ID')}</p>
                                        <p><strong>Status Pengiriman:</strong> {selectedTransaksi.status_pengiriman}</p>
                                        <p><strong>Status Pembelian:</strong> {selectedTransaksi.status_pembelian}</p>
                                        <p><strong>Verifikasi Pembayaran:</strong> {selectedTransaksi.verifikasi_pembayaran}</p>
                                        <p><strong>Tanggal Transaksi:</strong> {new Date(selectedTransaksi.created_at).toLocaleString('id-ID')}</p>
                                    </div>
                                    <div className="col-md-6">
                                        <h6>Detail Barang:</h6>
                                        {selectedTransaksi.detail.length > 0 ? (
                                            selectedTransaksi.detail.map((detailItem, idx) => (
                                                <div key={idx} className="mb-3 p-2 border rounded">
                                                    <p><strong>Nama Barang:</strong> {detailItem.barang.nama_barang}</p>
                                                    <p><strong>Harga Saat Transaksi:</strong> {detailItem.harga_saat_transaksi.toLocaleString('id-ID')}</p>
                                                    <p><strong>Deskripsi:</strong> {detailItem.barang.deskripsi}</p>
                                                    <p><strong>Status Barang:</strong> {detailItem.barang.status_barang}</p>

                                                    {/* Carousel for images within each detail item */}
                                                    <div id={`carouselGambar-${detailItem.id}`} className="carousel slide" data-bs-ride="carousel">
                                                        <div className="carousel-inner">
                                                            {detailItem.barang.gambar && (
                                                                <div className="carousel-item active">
                                                                    <img
                                                                        src={`http://localhost:8000/${detailItem.barang.gambar}`}
                                                                        className="d-block w-100 rounded"
                                                                        alt="Gambar 1"
                                                                        style={{ maxHeight: "300px", objectFit: "contain" }}
                                                                    />
                                                                </div>
                                                            )}
                                                            {detailItem.barang.gambar_dua && (
                                                                <div className="carousel-item">
                                                                    <img
                                                                        src={`http://localhost:8000/${detailItem.barang.gambar_dua}`}
                                                                        className="d-block w-100 rounded"
                                                                        alt="Gambar 2"
                                                                        style={{ maxHeight: "300px", objectFit: "contain" }}
                                                                    />
                                                                </div>
                                                            )}
                                                        </div>
                                                        {(detailItem.barang.gambar && detailItem.barang.gambar_dua) && ( 
                                                            <>
                                                                <button className="carousel-control-prev" type="button" data-bs-target={`#carouselGambar-${detailItem.id}`} data-bs-slide="prev">
                                                                    <span className="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                    <span className="visually-hidden">Previous</span>
                                                                </button>
                                                                <button className="carousel-control-next" type="button" data-bs-target={`#carouselGambar-${detailItem.id}`} data-bs-slide="next">
                                                                    <span className="carousel-control-next-icon" aria-hidden="true"></span>
                                                                    <span className="visually-hidden">Next</span>
                                                                </button>
                                                            </>
                                                        )}
                                                    </div>
                                                </div>
                                            ))
                                        ) : (
                                            <p>Tidak ada detail barang untuk transaksi ini.</p>
                                        )}
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

export default CRUDPengirimanPembeli;