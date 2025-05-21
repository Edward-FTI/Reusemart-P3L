import React, { useEffect, useState } from "react";
import axios from "axios";
import "bootstrap/dist/css/bootstrap.min.css";
import logo from "../assets/Logo/logo.png";
import "./DashboardCss.css";

const Dashboard = () => {
    const [barang, setBarang] = useState([]);
    const [loading, setLoading] = useState(true);
    const [selectedBarang, setSelectedBarang] = useState(null); // Untuk barang yang dipilih

    useEffect(() => {
        const fetchBarang = async () => {
            try {
                const response = await axios.get("http://localhost:8000/api/barang");
                setBarang(response.data.data);
                setLoading(false);
            } catch (error) {
                console.error("Error fetching barang data:", error);
                setLoading(false);
            }
        };

        fetchBarang();
    }, []);

    const handleModalOpen = (item) => {
        // Buat array gambar dari properti `gambar` dan `gambar_dua`
        const gambarArray = [];
        if (item.gambar) gambarArray.push(item.gambar);
        if (item.gambar_dua) gambarArray.push(item.gambar_dua);

        // Tambahkan array ke item dan simpan ke state
        setSelectedBarang({ ...item, gambar_array: gambarArray });
    };


    const handleModalClose = () => {
        setSelectedBarang(null);
    };

    if (loading) {
        return <p>Loading...</p>;
    }

    return (
        <div>
            <header className="bg-white shadow-sm sticky-top">
                <div className="container py-2">
                    <div className="d-flex align-items-center justify-content-between">
                        <a className="d-flex align-items-center logo" href="#">
                            <img src={logo} alt="Logo" width="35" />
                            <h5 className="ms-2 mb-0 text-success">reusemart</h5>
                        </a>
                        <div className="flex-grow-1 mx-4">
                            <form className="d-flex">
                                <input type="text" className="form-control search-box" placeholder="Cari di reusemart" />
                                <button className="btn btn-outline-secondary ms-2" type="submit">
                                    <img
                                        src="https://img.icons8.com/?size=100&id=132&format=png&color=000000"
                                        alt=""
                                        width="15"
                                    />
                                </button>
                            </form>
                        </div>
                        <div className="d-flex align-items-center">
                            <button className="btn btn-outline-success me-2">Masuk</button>
                            <button className="btn btn-outline-success me-2">Daftar</button>
                            <i className="bi bi-cart3 ms-3 fs-4"></i>
                        </div>
                    </div>
                </div>
            </header>

            <main className="container mt-5">
                <div className="row row-cols-1 row-cols-md-5 g-2">
                    {barang.map((item) => (
                        <div className="col" key={item.id}>
                            <div
                                className="card p-2"
                                style={{ cursor: "pointer" }}
                                onClick={() => handleModalOpen(item)}
                            >
                                <img src={`http://localhost:8000/${item.gambar}`} className="card-img-top" alt={item.nama_barang} />
                                <div className="card-body pt-2 pb-1">
                                    <h5 className="card-title mb-1">{item.nama_barang}</h5>
                                    <p className="card-text fw-semibold">Rp {item.harga_barang}</p>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            </main>

            {/* Modal */}
            {selectedBarang && (
                <div className="modal show d-block" tabIndex="-1">
                    <div className="modal-dialog">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5 className="modal-title">{selectedBarang.nama_barang}</h5>
                                <button
                                    type="button"
                                    className="btn-close"
                                    onClick={handleModalClose}
                                ></button>
                            </div>
                            <div className="modal-body">
                                {/* Carousel */}
                                <div id="carouselModal" className="carousel slide" data-bs-ride="carousel">
                                    <div className="carousel-inner border rounded">
                                        {selectedBarang.gambar_array?.map((gambar, index) => (
                                            <div
                                                className={`carousel-item ${index === 0 ? "active" : ""}`}
                                                key={index}
                                            >
                                                <img
                                                    src={`http://localhost:8000/${gambar}`}
                                                    className="d-block mx-auto img-fluid p-3"
                                                    style={{ maxHeight: "300px", objectFit: "contain" }}
                                                    alt={`Gambar ${index + 1}`}
                                                />
                                            </div>
                                        ))}
                                    </div>
                                    {selectedBarang.gambar_array?.length > 1 && (
                                        <>
                                            <button
                                                className="carousel-control-prev"
                                                type="button"
                                                data-bs-target="#carouselModal"
                                                data-bs-slide="prev"
                                            >
                                                <span className="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span className="visually-hidden">Previous</span>
                                            </button>
                                            <button
                                                className="carousel-control-next"
                                                type="button"
                                                data-bs-target="#carouselModal"
                                                data-bs-slide="next"
                                            >
                                                <span className="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span className="visually-hidden">Next</span>
                                            </button>
                                        </>
                                    )}
                                </div>
                                <br/>
                                <p>Nama Barang: {selectedBarang.nama_barang}</p>
                                <p>Harga: Rp{ selectedBarang.harga_barang }</p>
                                <p>Status Garansi: { selectedBarang.status_garansi}</p>
                                <p>Deskripsi: { selectedBarang.deskripsi}</p>
                            </div>
                            <div className="modal-footer">
                                <button
                                    type="button"
                                    className="btn btn-secondary"
                                    onClick={handleModalClose}
                                >
                                    Tutup
                                </button>
                                <button type="button" className="btn btn-success">Beli</button>
                            </div>
                        </div>
                    </div>
                </div>
            )}

        </div>
    );
};

export default Dashboard;
