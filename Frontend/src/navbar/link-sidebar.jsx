
import React from "react";
import './link-sidebar-css.css';
import { Link } from 'react-router-dom';

const LinkSidebar = () => {

    const user = JSON.parse(sessionStorage.getItem("user"));
    const role = user?.role;

    return (
        <div className="d-flex flex-column gap-3">
            {/* Hanya admin */}
            {role === "Admin" && (
                <>
                    <Link to="" className="d-flex align-items-center gap-2 text-white link-side">
                        <img src="https://img.icons8.com/?size=100&id=iPqKoSmxmAyJ&format=png&color=000000" alt="" width="30"
                            className="d-inline-block" />
                        Dashboard
                    </Link>
                    <Link to="pegawai/" className="d-flex align-items-center gap-2 text-white link-side">
                        <img src="https://img.icons8.com/?size=100&id=13042&format=png&color=000000" alt="" width="30"
                            className="d-inline-block" />
                        Data Pegawai
                    </Link>
                    <Link to="organisasi/" className="d-flex align-items-center gap-2 text-white link-side">
                        <img src="https://img.icons8.com/?size=100&id=13547&format=png&color=000000" alt="" width="30"
                            className="d-inline-block" />
                        Data Organisasi
                    </Link>
                </>
            )}

            {/* Customer Service */}
            {/* {role === "Customer Service" && (
                <Link to="/customer-service/penitip" className="d-flex align-items-center gap-2 text-white link-side">
                    <img src="https://img.icons8.com/?size=100&id=aurymxpl98YH&format=png&color=000000" alt="" width="30"
                        className="d-inline-block" />
                    Kelola Penitip
                </Link>
            )} */}
    
            {/* Pegawai Gudang */}
            {role === "Pegawai Gudang" && (
                <>
                    <Link to="barang-titipan" className="d-flex align-items-center gap-2 text-white link-side">
                        <img src="https://img.icons8.com/?size=100&id=hSUoULMc0FvV&format=png&color=000000" alt="" width="30"
                            className="d-inline-block" />
                        Barang Titipan
                    </Link>

                    <Link to="pengiriman/pembeli" className="d-flex align-items-center gap-2 text-white link-side">
                        <img src="https://img.icons8.com/?size=100&id=11910&format=png&color=000000" alt="" width="30"
                            className="d-inline-block" />
                        Pengiriman
                    </Link>
                </>
            )}

            {role === "Owner" && (
                <>
                    <Link to="request-donasi" className="d-flex align-items-center gap-2 text-white link-side">
                        <img src="https://img.icons8.com/?size=100&id=gEoGYhaNOcQl&format=png&color=000000" alt="" width="30"
                            className="d-inline-block" />
                        Request Donasi
                    </Link>

                    <Link to="penjualan-bulanan" className="d-flex align-items-center gap-2 text-white link-side">
                        <img src="https://img.icons8.com/?size=100&id=11890&format=png&color=000000" alt="" width="30"
                            className="d-inline-block" />
                        Laporan Penjualan Bulanan
                    </Link>

                    <Link to="komisi-bulanan" className="d-flex align-items-center gap-2 text-white link-side">
                        <img src="https://img.icons8.com/?size=100&id=JI2bnOlUlrmw&format=png&color=000000" alt="" width="30"
                            className="d-inline-block" />
                        Laporan Komisi Bulanan
                    </Link>

                    <Link to="stok-gudang" className="d-flex align-items-center gap-2 text-white link-side">
                        <img src="https://img.icons8.com/?size=100&id=j87nOIHCmSZK&format=png&color=000000" alt="" width="30"
                            className="d-inline-block" />
                        Laporan Stok Gudang
                    </Link>
                </>
            )}

        </div>
    );

}
export default LinkSidebar;
