import React, { useEffect, useState } from "react";
// import { toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { toast } from "sonner";
import Select from "react-select";
import jsPDF from "jspdf";
import { Link } from "react-router-dom";



import {
    GetAllBarang,
    CreateBarang,
    UpdateBarang,
    DeleteBarang
} from "../Api/apiBarangQC";
import { GetAllKategori } from "../Api/apiKategori";
import { GetAllPenitip } from "../Api/apiPenitip";
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

const CRUDBarangTitipan = () => {
    const [barangList, setBarangList] = useState([]);

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


    const handleSubmit = async (e) => {
        e.preventDefault();

        try {
            const formData = new FormData();
            formData.append('id', form.id);
            formData.append('id_penitip', form.id_penitip);
            formData.append('id_kategori', form.id_kategori);
            formData.append('tgl_penitipan', form.tgl_penitipan);
            formData.append('nama_barang', form.nama_barang);
            formData.append('harga_barang', form.harga_barang);
            formData.append('berat_barang', form.berat_barang);
            formData.append('deskripsi', form.deskripsi);
            formData.append('status_garansi', form.status_garansi);
            formData.append('status_barang', form.status_barang);

            if (form.gambar) {
                formData.append('gambar', form.gambar);
            }
            if (form.gambar_dua) {
                formData.append('gambar_dua', form.gambar_dua);
            }

            if (isEdit) {
                // await UpdateBarang(formData);
                await UpdateBarang(form); // Kirim objek, bukan FormData
                toast.success('Berhasil update data barang');
            } else {
                await CreateBarang(form);
                toast.success('Berhasil menambahkan data barang');
            }

            resetForm();
            fetchBarang();
        } catch (error) {
            toast.error('Gagal menyimpan data barang');
            console.error(error);
        }
    };


    const handleEdit = (barang) => {
        setForm({ ...barang, gambar: '', gambar_dua: '' });
        setIsEdit(true);
        const modal = new window.bootstrap.Modal(document.getElementById('formModal'));
        modal.show();
    }

    const handleDelete = async (id) => {
        try {
            if (window.confirm('Yakin ingin menghapus data ini?')) {
                await DeleteBarang(id); // Menghapus barang berdasarkan ID
                toast.success('Data barang berhasil dihapus');
                fetchBarang(); // Mengambil data terbaru
            }
        } catch (error) {
            console.error("Gagal menghapus data barang:", error);
            toast.error('Gagal menghapus data barang');
        }
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

    const handleDownloadNota = (barangList) => {
        const doc = new jsPDF();

        // Header
        doc.setFont("times", "bold");
        doc.setFontSize(14);
        doc.text("Nota Penitipan Barang", 10, 15);

        doc.setFontSize(12);
        doc.setFont("times", "bold");
        doc.text("ReUse Mart", 20, 25);

        doc.setFont("times", "normal");
        doc.text("Jl. Green Eco Park No. 456 Yogyakarta", 20, 30);

        // Ambil barang pertama untuk informasi umum (karena penitip sama)
        const barangPertama = barangList[0];

        // Info Nota
        doc.setFontSize(11);
        doc.text(`No Nota                       : ${barangPertama.nomor_nota || '24.02.101'}`, 20, 40);

        const [tanggal, waktu] = barangPertama.tgl_penitipan.split(' ');
        const [tahun, bulan, hari] = tanggal.split('-');
        const formattedDate = `${hari}/${bulan}/${tahun} ${waktu}`;

        doc.text(`Tanggal penitipan        : ${formattedDate}`, 20, 45);
        doc.text(`Masa penitipan sampai: ${new Date(barangPertama.masa_penitipan).toLocaleDateString('id-ID')}`, 20, 50);

        // Penitip
        const penitip = `T${barangPertama.penitip.id} / ${barangPertama.penitip?.nama_penitip}`;
        const alamat_penitip = barangPertama.penitip?.alamat;

        doc.setFont("times", "bold");
        doc.text("Penitip :", 20, 60);

        doc.setFont("times", "normal");
        doc.text(penitip, 35, 60);
        doc.text(alamat_penitip, 20, 65);
        doc.text(`Delivery : Kurir ReUseMart (Cahyono)`, 20, 70);

        // Tampilkan daftar semua barang
        let y = 80;
        barangList.forEach((barang, index) => {
            doc.setFont("times", "normal");
            doc.text(`${barang.nama_barang}`, 20, y);
            doc.text(`${barang.harga_barang?.toLocaleString()}`, 90, y, { align: "right" });
            y += 5;

            if (barang.status_garansi != null) {
                doc.text(`Garansi ON ${barang.status_garansi}`, 20, y);
                y += 5;
            }
            doc.text(`Berat barang: ${barang.berat_barang} kg`, 20, y);
            y += 5;
        });

        // Footer
        y += 10;
        doc.text("Diterima dan QC oleh:", 50, y);
        doc.text(`P${barangPertama.pegawai?.id} - ${barangPertama.pegawai.nama}`, 50, y + 10);

        doc.setLineWidth(0.5);
        doc.rect(8, 10, 190, y + 10); // kotak sekitar seluruh isi

        doc.save(`Nota_${barangPertama.penitip.nama_penitip}.pdf`);
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
                  <button className="btn btn-sm btn-warning mb-1" onClick={() => handleEdit(b)}>
                    Edit
                  </button>
                  <button
                    className="btn btn-sm btn-danger mb-1"
                    onClick={() => {
                      if (window.confirm("Yakin ingin menghapus data ini?")) {
                        DeleteBarang(b.id).then(fetchBarang);
                      }
                    }}
                  >
                    Hapus
                  </button>
                  <button className="btn btn-sm btn-primary mb-1" onClick={() => handleShowDetail(b)}>
                    Detail
                  </button>
                  <button
                    className="btn btn-sm btn-success"
                    onClick={() => {
                      const barangPenitipTerkait = barangList.filter(
                        (barangItem) => barangItem.penitip.id === b.penitip.id
                      );
                      handleDownloadNota(barangPenitipTerkait);
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
