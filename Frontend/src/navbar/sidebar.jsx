import React from "react";
import LinkSidebar from "./link-sidebar";

const Sidebar = () => {
    return (
        <>
            <button className="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions"
                aria-controls="offcanvasWithBothOptions">
                <img src="https://img.icons8.com/?size=100&id=8113&format=png&color=FFFFFF" alt="" width="30" height="24"
                    className="d-inline-block align-text-top" />
            </button>

            <div className="offcanvas offcanvas-start bg-success" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions"
                aria-labelledby="offcanvasWithBothOptionsLabel">
                <div className="offcanvas-header">
                    <div className="d-flex align-items-center fw-bold fs-5 text-white">
                        <img src="{{ 'asset/logo.png' }}" alt="" width="50" className="d-inline-block" />
                        Reusemart
                    </div>
                    <button type="button" className="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>

                <div className="offcanvas-body">
                    <LinkSidebar/>
                </div>
            </div>
        </>
    )
}

export default Sidebar;