
import React from "react";
import './link-sidebar-css.css';
import { Link } from 'react-router-dom';

const LinkSidebar = () => {
    return (
        <div className="d-flex flex-column gap-3">
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
            {/* <a href="" className="d-flex align-items-center gap-2 text-white">
                <img src="https://img.icons8.com/?size=100&id=13042&format=png&color=000000" alt="" width="30"
                    className="d-inline-block" />
                Data User
            </a>
            <a href="" className="d-flex align-items-center gap-2 text-white">
                <img src="https://img.icons8.com/?size=100&id=aurymxpl98YH&format=png&color=000000" alt=""
                    width="30" className="d-inline-block" />
                Data Role
            </a>
            <a href="" className="d-flex align-items-center gap-2 text-white">
                <img src="https://img.icons8.com/?size=100&id=hSUoULMc0FvV&format=png&color=000000" alt=""
                    width="30" className="d-inline-block" />
                Data Barang
            </a> */}
        </div>
    )
}
export default LinkSidebar;
