import React from "react";
import Sidebar from "./sidebar";


const Navbar = () => {
    return (
        <nav className="navbar bg-success">
            <div className="container-fluid">
                <Sidebar />

                <a href="" className="d-flex n-items-center gap-1 text-white">
                    <img src="https://img.icons8.com/?size=100&id=24337&format=png&color=FFFFFF" alt=""
                        width="30" className="d-inline-block" />
                    Log out
                </a>
            </div>
        </nav>
    )
}
export default Navbar;

