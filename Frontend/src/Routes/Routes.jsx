import { createBrowserRouter, RouterProvider } from "react-router-dom";
import { Toaster } from "sonner";

import NavbarPage from "../Components/Navbar";
import Footer from "../Components/Footer";
import Dashboard from "../Components/Dashboard";
import Login from "../Components/Login";
import RegisterPage from "../Components/pageReg/regisPage";
import RegisterOrgPage from "../Components/pageReg/regisOrgPage";
import ForgetPassword from "../Components/ForgetPassword";
import AmbilBarang from "../Components/AmbilBarang";

// CUSTOMER SERVICE
import NavbarCustomer_Service from "../navbar/NavbarCustomer_Service";
import CRUDPenitip from "../Customer_Service/CRUDPenitip";
import CRUDDiskusi from "../Customer_Service/CRUDDiskusi";

// ADMIN
import CRUDJabatan from "../admin/CRUDJabatan";
import CRUDMercandise from "../admin/CRUDMercandise";
import CRUDOrganisasi from "../Admin/CRUDOrganisasi";

// PEGAWAI GUDANG
import CRUDBarangTitipan from "../Pegawai_Gudang/CRUDBarangTitipan";
import CRUDPengiriman from "../Pegawai_Gudang/CRUDPengiriman";

// CUSTOMER
import NavbarCustomer from "../navbar/NavbarCustomer";
import ShowProfileCustomer from "../Customers/ShowProfileCustomer";
import ShowHistoryCustomer from "../Customers/ShowHistoryCustomer";
import CRUDTransaksiPenjualanCustomer from "../Customers/CRUDTransaksiPenjualanCustomer";

// PENITIP
import ShowProfilePenitip from "../Penitip/ShowProfilePenitip.jsx";
import ShowHistoryPenitip from "../penitip/ShowHistoryPenitip";
import CRUDPenitipan from "../penitip/CRUDPenitipan";
import PengambilanBarangKembali from "../penitip/PengambilanBarangKembali";

//ALAMAT
import CRUDAlamat from "../pembeli/CrudAlamat.jsx";

// ORGANISASI
import CRUDTransaksiRequestDonasi from "../organisasi/CRUDTransaksiRequestDonasi";
import Layout from "../navbar/layout.jsx";
import Organisasi from "../Organisasi/Organisasi.jsx";

const router = createBrowserRouter([
  {
    path: "*",
    element: <div>Routes Not Found!</div>,
  },
  {
    path: "/",
    element: (
      <div>
        <NavbarPage />
        <Dashboard />
        <Footer />
      </div>
    ),
  },
  {
    path: "/login",
    element: <Login />,
  },
  {
    path: "/register",
    element: <RegisterPage />,
  },
  {
    path: "/register-org",
    element: <RegisterOrgPage />,
  },
  {
    path: "/forget-password",
    element: <ForgetPassword />,
  },
  {
    path: "/ambil-barang",
    element: <AmbilBarang />,
  },

  // CUSTOMER SERVICE
  {
    path: "/customer-service/penitip",
    element: (
      <div>
        <NavbarCustomer_Service />
        <CRUDPenitip />
        <Footer />
      </div>
    ),
  },
  {
    path: "/customer-service/diskusi",
    element: (
      <div>
        <NavbarCustomer_Service />
        <CRUDDiskusi />
        <Footer />
      </div>
    ),
  },

  // ADMIN
  {
    path: "/admin/jabatan",
    element: (
      <div>
        <CRUDJabatan />
        <Footer />
      </div>
    ),
  },
  {
    path: "/admin/pegawai",
    element: (
      <div>
        <Layout />
      </div>
    ),
  },
  {
    path: "/admin/mercandise",
    element: (
      <div>
        <CRUDMercandise />
        <Footer />
      </div>
    ),
  },
  {
    path: "/admin/organisasi",
    element: (
      <div>
        <CRUDOrganisasi />
        <Footer />
      </div>
    ),
  },

  // PEGAWAI GUDANG
  {
    path: "/gudang/barang-titipan",
    element: (
      <div>
        <NavbarPage />
        <CRUDBarangTitipan />
        <Footer />
      </div>
    ),
  },
  {
    path: "/gudang/pengiriman",
    element: (
      <div>
        <NavbarPage />
        <CRUDPengiriman />
        <Footer />
      </div>
    ),
  },

  // CUSTOMER
  {
    path: "/customer/profile",
    element: (
      <div>
        <NavbarPage />
        <ShowProfileCustomer />
        <Footer />
      </div>
    ),
  },
  {
    path: "/customer/history",
    element: (
      <div>
        <NavbarPage />
        <ShowHistoryCustomer />
        <Footer />
      </div>
    ),
  },
  {
    path: "/customer/diskusi",
    element: (
      <div>
        <NavbarPage />
        <CRUDDiskusi />
        <Footer />
      </div>
    ),
  },
  {
    path: "/customer/transaksi",
    element: (
      <div>
        <NavbarPage />
        <CRUDTransaksiPenjualanCustomer />
        <Footer />
      </div>
    ),
  },

  //ALAMAT
  {
    path: "/pembeli/alamat",
    element: (
      <div>
        {/* <NavbarPage /> */}
        <CRUDAlamat />
      </div>
    ),
  },

  // PENITIP
  {
    path: "/penitip/profile",
    element: (
      <div>
        {/* <NavbarPage /> */}
        <ShowProfilePenitip />
        {/* <Footer /> */}
      </div>
    ),
  },
  {
    path: "/penitip/history",
    element: (
      <div>
        <NavbarPage />
        <ShowHistoryPenitip />
        <Footer />
      </div>
    ),
  },
  {
    path: "/penitip/penitipan",
    element: (
      <div>
        <NavbarPage />
        <CRUDPenitipan />
        <Footer />
      </div>
    ),
  },
  {
    path: "/penitip/pengambilan",
    element: (
      <div>
        <NavbarPage />
        <PengambilanBarangKembali />
        <Footer />
      </div>
    ),
  },

  // ORGANISASI
  {
    path: "/organisasi/transaksi-request-donasi",
    element: (
      <div>
        <NavbarPage />
        <CRUDTransaksiRequestDonasi />
        <Footer />
      </div>
    ),
  },
  {
    path: "/ujiankelas",
    element: (
      <div>
        {/* <NavbarPage /> */}
        <Organisasi />
        {/* <Footer /> */}
      </div>
    ),
  },
]);

const AppRouter = () => {
  return (
    <>
      <Toaster position="top-center" richColors />
      <RouterProvider router={router} />
    </>
  );
};

export default AppRouter;
