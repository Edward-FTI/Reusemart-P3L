import React, { useEffect, useState } from "react";
// import { toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { toast } from "sonner";
import Select from "react-select";

import {
  GetTransaksi_Penitipan,
  GetTransaksi_PenitipanById,
  GetTransaksi_PenitipanByNama,
  CreatetTransaksi_Penitipan,
  UpdatetTransaksi_Penitipan,
} from "../Api/apiTransaksi_penitip";

import { GetAllKategori } from "../Api/apiKategori";
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

const Transaksi_penitip = () => {
    const [barangList, setBarangList] = useState([]);
    const [penitipList, setPenitipList] = useState([]);
    const [kategoriList, setKategoriList] = useState([]);
    const [isEdit, setIsEdit] = useState(false);
    const [searchTerm, setSearchTerm] = useState('');
    const [filteredBarangList, setFilteredBarangList] = useState([]);
    const [selectedBarang, setSelectedBarang] = useState(null);

    const [form, setForm] = useState({
        id: '',
        id_kategori: '',
        tgl_penitipan: '',
        penambahan_durasi: '',
        nama_barang: '',
        harga_barang: '',
        deskripsi: '',
        status_garansi: '',
        status_barang: '',
        gambar: '',
        gambar_dua: ''
    });

    const [loading, setLoading] = useState(false);

    const fetchBarang = async () => {
        setLoading(true);
        try {
            const data = await GetTransaksi_Penitipan();
            setBarangList(data);
            setFilteredBarangList(data);
        } catch (error) {
            toast.error("Gagal mengambil data barang");
        } finally {
            setLoading(false);
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
        } catch (error) {
            toast.error('Gagal mengambil data kategori');
        }
    };

    const fetchPenitip = async () => {
        try {
            const data = await GetAllPenitip();
            setPenitipList(data);
        } catch (error) {
            // toast.error('Gagal mengambil data penitip');
        }
    };

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

    const handleUpdateTanggalPenitipan = async (barang) => {
        const sisaHariStr = getSisaWaktuPenitipan(barang.tgl_penitipan, barang.masa_penitipan);
        const sisaHari = sisaHariStr === 'Kadaluarsa' ? 0 : parseInt(sisaHariStr);

        // Jika masa penitipan habis dan belum pernah diperpanjang (null atau 0)
        if ((sisaHari <= 0) && (barang.penambahan_durasi === null)) {
            try {
                const newMasaPenitipan = new Date(barang.masa_penitipan);
                newMasaPenitipan.setDate(newMasaPenitipan.getDate() + 30);

                await UpdatetTransaksi_Penitipan(barang.id, {
                    ...barang,
                    masa_penitipan: newMasaPenitipan.toISOString().split('T')[0],
                    penambahan_durasi: 1 // Set penambahan_durasi menjadi 1

                    
                });
                toast.success("Berhasil menambah 30 hari masa penitipan");
                fetchBarang();
            } catch (error) {
                toast.error("Gagal memperbarui masa penitipan");
            }
        } else {
            toast.info("Penambahan durasi hanya bisa dilakukan jika masa penitipan habis dan belum pernah diperpanjang.");
        }
    };


   const getSisaWaktuPenitipan = (tglPenitipan, masaPenitipan) => {
    const today = new Date();
    const endDate = new Date(masaPenitipan);
    const diffTime = endDate - today;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    return diffDays < 0 ? 'Kadaluarsa' : `${diffDays} hari`;
    };


    const handleShowDetail = (barang) => {
        setSelectedBarang(barang);
        const modal = new window.bootstrap.Modal(document.getElementById('detailModal'));
        modal.show();
    };

    return (
        <div className="container mt-5 bg-white p-4 rounded shadow">
            <div className="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2>Data Barang</h2>
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
                            style={{ width: "250px" }}
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
                        {/* <th>Nama Penitip</th> */}
                        <th>Kategori Barang</th>
                        <th>Tanggal Penitipan</th>
                        <th>Masa Penitipan</th>
                        <th>Sisa Masa penitipan</th>
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
                                {/* <td>{b.penitip?.nama_penitip}</td> */}
                                <td>{b.kategori_barang?.nama_kategori || "Kategori tidak ditemukan"}</td>
                                <td>{b.tgl_penitipan}</td>
                                <td>{b.masa_penitipan}</td>
                                <td>{getSisaWaktuPenitipan(b.tgl_penitipan, b.masa_penitipan)}</td>
                                <td>{b.nama_barang}</td>
                                <td>{b.harga_barang}</td>
                                <td>{b.deskripsi}</td>
                                <td>{b.status_barang}</td>
                                <td>
                                    <div className="d-flex flex-column">
                                        <button
                                            className="btn btn-sm btn-warning me-2"
                                            disabled={
                                                (getSisaWaktuPenitipan(b.tgl_penitipan, b.masa_penitipan) === 'Kadaluarsa' && b.penambahan_durasi === null)
                                            }
                                            onClick={() => handleUpdateTanggalPenitipan(b)}
                                            >
                                            Update Tgl Penitipan
                                        </button>

                                        <button
                                            className="btn btn-sm btn-primary me-2 mt-2"
                                            onClick={() => handleShowDetail(b)}
                                        >
                                            Detail
                                        </button>   
                                    </div>
                                </td>
                            </tr>
                        ))
                    ) : (
                        <tr>
                            <td colSpan="10" className="text-center fs-2">Belum ada data barang</td>
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
                                        <p><strong>Status Garansi:</strong> {selectedBarang.status_garansi}</p>
                                        <p><strong>Penitip:</strong> {selectedBarang.penitip?.nama_penitip}</p>
                                        <p><strong>Kategori:</strong> {selectedBarang.kategori_barang?.nama_kategori}</p>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Transaksi_penitip;
