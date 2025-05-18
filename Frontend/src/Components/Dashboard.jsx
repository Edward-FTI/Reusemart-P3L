import React from "react";

import "bootstrap/dist/css/bootstrap.min.css";
// import "bootstrap-icons/font/bootstrap-icons.css";
import Login from "./Login";

import { Link } from "react-router-dom";
import logo from "../assets/Logo/logo.png";
import hp from "./gambar/hp";
import sepatu from "./gambar/sepatu";
import tas from "./gambar/tas";
import meja from "./gambar/meja";
import alatTulis from "./gambar/AlatTulis";
import mainan from "./gambar/mainan";
import "./DashboardCss.css";

const Dashboard = () => {
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
                                    <img src="https://img.icons8.com/?size=100&id=132&format=png&color=000000"
                                        alt="" width="15" />
                                </button>
                            </form>
                        </div>

                        <div class="d-flex align-items-center">
                            <Link to="/login">
                                <button className="btn btn-outline-success me-2">Masuk</button>
                            </Link>

                            <Link to="/register">
                                <button className="btn btn-outline-success me-2">Daftar</button>
                            </Link>
                            <i class="bi bi-cart3 ms-3 fs-4"></i>
                        </div>
                    </div>

                    <div className="category-links mt-2">
                        <a href="#elektronik">Elektronik & Gadget</a>
                        <a href="#pakaianAksesori">Pakaian & Aksesori</a>
                        <a href="#perabotan">Perabotan Rumah Tangga</a>
                        <a href="#peralatanSekolah">Buku, Alat Tulis, & Peralatan Sekolah</a>
                        <a href="#mainan">Hobi, Mainan, & Koleksi</a>
                        <br />
                        <a href="#">Perlengkapan Bayi & Anak</a>
                        <a href="#">Otomotif & Aksesori</a>
                        <a href="#">Perlengkapan Taman & Outdoor</a>
                        <a href="#">Peralatan Kantor & Industri</a>
                        <a href="#">Kosmetik & Perawatan Diri</a>
                    </div>
                </div>
            </header>

            <main className="container mt-5">
                <div id="elektronik">
                    {/* <div className="row row-cols-1 row-cols-md-5 g-3">
                        {products.map((product, index) => (
                            <div className="col" key={index}>
                                <a>
                                    <div className="card p-2">
                                        <img src={product.image} className="card-img-top" alt={product.title} />
                                        <div className="card-body pt-2 pb-1">
                                            <h5 className="card-title mb-1">{product.title}</h5>
                                            <p className="card-text fw-semibold">{product.price}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        ))}
                    </div> */}

                    <div className="row row-cols-1 row-cols-md-5 g-3">
                        {hp.map((hp, index) => (
                            <div className="col" key={index}>
                                <a>
                                    <div className="card p-2">
                                        <img src={hp.image} className="card-img-top" alt={hp.title} />
                                        <div className="card-body pt-2 pb-1">
                                            <h5 className="card-title mb-1">{hp.title}</h5>
                                            <p className="card-text fw-semibold">{hp.price}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        ))}
                    </div>
                </div>

                <div id="pakaianAksesori">
                    <div className="row row-cols-1 row-cols-md-5 g-3">
                        {sepatu.map((sepatu, index) => (
                            <div className="col" key={index}>
                                <a>
                                    <div className="card p-2">
                                        <img src={sepatu.image} className="card-img-top" alt={sepatu.title} />
                                        <div className="card-body pt-2 pb-1">
                                            <h5 className="card-title mb-1">{sepatu.title}</h5>
                                            <p className="card-text fw-semibold">{sepatu.price}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        ))}
                    </div>

                    <div className="row row-cols-1 row-cols-md-5 g-3">
                        {tas.map((tas, index) => (
                            <div className="col" key={index}>
                                <a>
                                    <div className="card p-2">
                                        <img src={tas.image} className="card-img-top" alt={tas.title} />
                                        <div className="card-body pt-2 pb-1">
                                            <h5 className="card-title mb-1">{tas.title}</h5>
                                            <p className="card-text fw-semibold">{tas.price}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        ))}
                    </div>
                </div>

                <div id="perabotan">
                    <div className="row row-cols-1 row-cols-md-5 g-3">
                        {meja.map((meja, index) => (
                            <div className="col" key={index}>
                                <a>
                                    <div className="card p-2">
                                        <img src={meja.image} className="card-img-top" alt={meja.title} />
                                        <div className="card-body pt-2 pb-1">
                                            <h5 className="card-title mb-1">{meja.title}</h5>
                                            <p className="card-text fw-semibold">{meja.price}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        ))}
                    </div>
                </div>

                <div id="peralatanSekolah">
                    <div className="row row-cols-1 row-cols-md-5 g-3">
                        {alatTulis.map((alatTulis, index) => (
                            <div className="col" key={index}>
                                <a>
                                    <div className="card p-2">
                                        <img src={alatTulis.image} className="card-img-top" alt={alatTulis.title} />
                                        <div className="card-body pt-2 pb-1">
                                            <h5 className="card-title mb-1">{alatTulis.title}</h5>
                                            <p className="card-text fw-semibold">{alatTulis.price}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        ))}
                    </div>
                </div>

                <div id="mainan">
                    <div className="row row-cols-1 row-cols-md-5 g-3">
                        {mainan.map((mainan, index) => (
                            <div className="col" key={index}>
                                <a>
                                    <div className="card p-2">
                                        <img src={mainan.image} className="card-img-top" alt={mainan.title} />
                                        <div className="card-body pt-2 pb-1">
                                            <h5 className="card-title mb-1">{mainan.title}</h5>
                                            <p className="card-text fw-semibold">{mainan.price}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        ))}
                    </div>
                </div>


            </main>
        </div>
    );
};

export default Dashboard;
