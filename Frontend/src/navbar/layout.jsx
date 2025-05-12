import React from "react";
import Navbar from "./navbar";
// import Pegawai from "../pegawai/pegawai";
import CRUDPegawai from "../admin/CRUDPegawai";
import { Outlet } from "react-router-dom";

const Layout = () => {
    return (
        <>
            <Navbar />
            <CRUDPegawai/>
            {/* <Outlet /> */}
        </>
    )
}

export default Layout