import React from "react";
import "bootstrap/dist/css/bootstrap.min.css";
import { Link } from "react-router-dom"; // ✅ Tambahkan ini
import logo from "../assets/Logo/logo.png";
import AL12 from "../assets/Elektronik/Laptop/AL12.png";
import AL14 from "../assets/Elektronik/Laptop/AL14.png";
import HP14 from "../assets/Elektronik/Laptop/HP14.png";
import HP14S from "../assets/Elektronik/Laptop/HP14S.png";
import LOQ15 from "../assets/Elektronik/Laptop/LOQ15.png";
import LOQ15X from "../assets/Elektronik/Laptop/LOQ15X.png";
import MSI_Cyborg_14 from "../assets/Elektronik/Laptop/MSI_Cyborg_14.png";
import MSI_Cyborg_15 from "../assets/Elektronik/Laptop/MSI_Cyborg_15.png";
import "./DashboardCss.css";

const Dashboard = () => {
  const products = [
    { title: "Acer Lite 12", price: "Rp7.499.000", image: AL12 },
    { title: "Acer Lite 14", price: "Rp12.999.000", image: AL14 },
    { title: "HP 14", price: "Rp18.599.000", image: HP14 },
    { title: "HP 14S", price: "Rp20.499.000", image: HP14S },
    { title: "Lenovo LOQ 15", price: "Rp2.399.000", image: LOQ15 },
    { title: "Lenovo LOQ 15", price: "Rp8.499.000", image: LOQ15X },
    { title: "MSI Cyborg 14", price: "Rp13.499.000", image: MSI_Cyborg_14 },
    { title: "MSI Cyborg 15", price: "Rp13.499.000", image: MSI_Cyborg_15 },
  ];

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
                <input
                  type="text"
                  className="form-control search-box"
                  placeholder="Cari di reusemart"
                />
                <button
                  className="btn btn-outline-secondary ms-2"
                  type="submit"
                >
                  <img
                    src="https://img.icons8.com/?size=100&id=132&format=png&color=000000"
                    alt=""
                    width="15"
                  />
                </button>
              </form>
            </div>

            <div className="d-flex align-items-center">
              {/* ✅ Ganti button jadi Link */}
              <Link to="/login" className="btn btn-outline-success me-2">
                Masuk
              </Link>
              <Link to="/register" className="btn btn-success">
                Daftar
              </Link>
              <i className="bi bi-cart3 ms-3 fs-4"></i>
            </div>
          </div>

          <div className="category-links mt-2">
            <a href="#">Elektronik & Gadget</a>
            <a href="#">Pakaian & Aksesori</a>
            <a href="#">Perabotan Rumah Tangga</a>
            <a href="#">Buku, Alat Tulis, & Peralatan Sekolah</a>
            <a href="#">Hobi, Mainan, & Koleksi</a>
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
        <div className="row row-cols-1 row-cols-md-5 g-3">
          {products.map((product, index) => (
            <div className="col" key={index}>
              <div className="card p-2">
                <img
                  src={product.image}
                  className="card-img-top"
                  alt={product.title}
                />
                <div className="card-body pt-2 pb-1">
                  <h5 className="card-title mb-1">{product.title}</h5>
                  <p className="card-text fw-semibold">{product.price}</p>
                </div>
              </div>
            </div>
          ))}
        </div>
      </main>
    </div>
  );
};

export default Dashboard;
