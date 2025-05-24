import React, { useEffect } from "react";
import { useLocation } from "react-router-dom";
import LinkSidebar from "./link-sidebar";

const Sidebar = () => {
    const location = useLocation();

    useEffect(() => {
        // Saat route berubah, cari offcanvas dan backdrop lalu tutup & hapus
        const offcanvasEl = document.querySelector('.offcanvas.show');
        if (offcanvasEl) {
            const offcanvasInstance = bootstrap.Offcanvas.getInstance(offcanvasEl);
            if (offcanvasInstance) {
                offcanvasInstance.hide(); // tutup secara paksa
            }
        }

        // Hapus backdrop yang mungkin tertinggal
        document.querySelectorAll('.offcanvas-backdrop').forEach(el => el.remove());
    }, [location]);

    return (
        <>
            <button className="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions"
                aria-controls="offcanvasWithBothOptions">
                <img src="https://img.icons8.com/?size=100&id=8113&format=png&color=FFFFFF" alt="" width="30" height="24"
                    className="d-inline-block align-text-top" />
            </button>

            <div className="offcanvas offcanvas-start bg-success" data-bs-scroll="true"
                tabIndex={-1} id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
                <div className="offcanvas-header">
                    <div className="d-flex align-items-center fw-bold fs-5 text-white">
                        <img src="{{ 'asset/logo.png' }}" alt="" width="50" className="d-inline-block" />
                        Reusemart
                    </div>
                    <button type="button" className="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>

                <div className="offcanvas-body">
                    <LinkSidebar />
                </div>
            </div>
        </>
    )
}

export default Sidebar;
