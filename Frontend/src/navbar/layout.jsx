import React from "react";
import Navbar from "./navbar";
import Pegawai from "../pegawai/pegawai";
import { Outlet } from "react-router-dom";

const Layout = () => {
    return (
        <>
            <Navbar />
            <Outlet />
        </>
    )
}

export default Layout