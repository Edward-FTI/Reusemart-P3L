import { createBrowserRouter, RouterProvider } from "react-router-dom";
import { Toaster } from "sonner";

// Komponen Umum
import NavbarPage from "../Components/Navbar";
import Footer from "../Components/Footer";
import Dashboard from "../Components/Dashboard";
import Login from "../Components/Login";
import RegisterPage from "../Components/pageReg/regisPage";
import RegisterOrgPage from "../Components/pageReg/regisOrgPage";
import ForgetPassword from "../Components/ForgetPassword";
import ResetPassword from "../Components/resetPassword";
import AmbilBarang from "../Components/AmbilBarang";

// CUSTOMER SERVICE
import NavbarCustomer_Service from "../navbar/NavbarCustomer_Service";
import CRUDPenitip from "../Customer_Service/CRUDPenitip";
import CRUDDiskusi from "../Customer_Service/CRUDDiskusi";

// ADMIN
import Admin from "../Admin/Admin";
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
import ShowProfilePenitip from "../Penitip/ShowProfilePenitip";
import ShowHistoryPenitip from "../penitip/ShowHistoryPenitip";
import CRUDPenitipan from "../penitip/CRUDPenitipan";
import PengambilanBarangKembali from "../penitip/PengambilanBarangKembali";

// ORGANISASI
import CRUDTransaksiRequestDonasi from "../Organisasi/CRUDTransaksiRequestDonasi";
import Organisasi from "../Organisasi/Organisasi";
import Layout from "../navbar/layout";

// OWNER
import Owner from "../Owner/Owner";

// Detail Barang
import DetailBarang from "../Components/DetailBarang";

// ALAMAT
import CRUDAlamat from "../pembeli/CrudAlamat";
import CRUDPegawai from "../admin/CRUDPegawai";

const router = createBrowserRouter([
  {
    path: "*",
    element: <div>Routes Not Found!</div>,
  },
  {
    path: "/",
    element: <Dashboard />,
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
    path: "/reset-password",
    element: <ResetPassword />,
  },
  {
    path: "/ambil-barang",
    element: <AmbilBarang />,
  },
  {
    path: "/alamat",
    element: <CRUDAlamat />
  },

  // ADMIN
  {
    path: "/admin",
    element: <Layout />,
    children: [
      {
        path: "/admin",
        element: <Admin/>// atau komponen dashboard admin Anda
      },
      {
        path: "pegawai",
        element: <CRUDPegawai />,
      },
      {
        path: "jabatan",
        element: <CRUDJabatan />,
      },
      {
        path: "mercandise",
        element: <CRUDMercandise />,
      },
      {
        path: "organisasi",
        element: <CRUDOrganisasi />,
      },
    ],
  },


  // CUSTOMER SERVICE
  {
    path: "/customer-service/penitip",
    element: (
      <div>
        <NavbarCustomer_Service />
        <CRUDPenitip />
      </div>
    ),
  },
  {
    path: "/customer-service/diskusi",
    element: (
      <div>
        <CRUDDiskusi />
        <Footer />
      </div>
    ),
  },

  // PEGAWAI GUDANG
  {
    path: "/gudang/barang-titipan",
    element: <CRUDBarangTitipan />,
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
        <NavbarCustomer />
        <ShowProfileCustomer />
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

  // PENITIP
  {
    path: "/penitip/profile",
    element: (
      <div>
        <NavbarPage />
        <ShowProfilePenitip />
        <Footer />
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
        {/* <NavbarPage /> */}
        <PengambilanBarangKembali />
        {/* <Footer /> */}
      </div>
    ),
  },

  // ORGANISASI
  {
    path: "/organisasi",
    element: (
      <div>
        <NavbarPage />
        <Organisasi />
        <Footer />
      </div>
    ),
  },
  {
    path: "/organisasi/transaksi-request-donasi",
    element: (
      <div>
        <CRUDTransaksiRequestDonasi />
      </div>
    ),
  },

  // OWNER
  {
    path: "/owner",
    element: (
      <div>
        {/* <NavbarPage /> */}
        <Owner />
        {/* <Footer /> */}
      </div>
    ),
  },

  //Detail Barang
  {
    path: "/detail/:id",
    element: (
      <DetailBarang />
    )
  }
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
