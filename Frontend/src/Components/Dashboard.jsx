import React, { useEffect, useState } from "react";
import axios from "axios";
import "bootstrap/dist/css/bootstrap.min.css";
import { Link } from "react-router-dom";
import logo from "../assets/Logo/logo.png";
import "./DashboardCss.css";

const Dashboard = () => {
    const [barang, setBarang] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        // Fetch data dari API
        const fetchBarang = async () => {
            try {
                const response = await axios.get("http://localhost:8000/api/barang"); // Ganti dengan URL backend kamu
                setBarang(response.data.data);
                setLoading(false);
            } catch (error) {
                console.error("Error fetching barang data:", error);
                setLoading(false);
            }
        };

        fetchBarang();
    }, []);

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
                            <Link to="/login">
                                <button className="btn btn-outline-success me-2">Masuk</button>
                            </Link>
                            <Link to="/register">
                                <button className="btn btn-outline-success me-2">Daftar</button>
                            </Link>
                            <i className="bi bi-cart3 ms-3 fs-4"></i>
                        </div>
                    </div>
                </div>
            </header>

            <main className="container mt-5">
                <div className="row row-cols-1 row-cols-md-5 g-2">
                    {barang.map((item) => (
                        <div className="col" key={item.id}>
                            <a>
                                <div className="card p-2">
                                    <img src={`http://localhost:8000/${item.gambar}`} className="card-img-top" alt={item.nama_barang} />
                                    {/* <img src={`http://localhost:8000/${item.gambar}`} /> */}

                                    <div className="card-body pt-2 pb-1">
                                        <h5 className="card-title mb-1">{item.nama_barang}</h5>
                                        <p className="card-text fw-semibold">Rp {item.harga_barang}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    ))}
                </div>
            </main>
        </div>
    );
};

export default Dashboard;
