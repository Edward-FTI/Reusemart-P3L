import { createBrowserRouter, RouterProvider } from "react-router-dom";
import { Toaster } from "sonner";

import NavbarPage from "../Components/Navbar";
import Footer from "../Components/Footer";
import Dashboard from "../Components/Dashboard";
import Login from "../Components/Login";
import Register from "../Components/Register";
import ForgetPassword from "../Components/ForgetPassword";
import AmbilBarang from "../Components/AmbilBarang";

//CUSTOMER SERVICE
import CRUDPenitip from "../Customer_Service/CRUDPenitip";
import CRUDDiskusi from "../Customer_Service/CRUDDiskusi";

//ADMIN 
import CRUDJabatan from "../admin/CRUDJabatan";
import CRUDPegawai from "../admin/CRUDPegawai";
import CRUDMercandise from "../admin/CRUDMercandise";
import CRUDOrganisasi from "../Admin/CRUDOrganisasi";
// import ShowAdmin from "../admin/DaftarCustomer";
// import TopNavBarAdmin from "../admin/TopNavBarCS";

//PEGAWAI GUDANG
import CRUDBarangTitipan from "../Pegawai_Gudang/CRUDBarangTitipan";
import CRUDPengiriman from "../Pegawai_Gudang/CRUDPengiriman";

//Customer
import ShowProfileCustomer from "../Customers/ShowProfileCustomer";
import ShowHistoryCustomer from "../Customers/ShowHistoryCustomer";
import CRUDDiskusiCustomer from "../Customers/CRUDDiskusiCustomer";
import CRUDTransaksiPenjualanCustomer from "../Customers/CRUDTransaksiPenjualanCustomer";
// import Rating from "../customer/Rating";
// import PenukaranReward from "../customer/PenukaranReward";

//Penitip
import ShowProfilePenitip from "../penitip/ShowProfilePenitip";
import ShowHistoryPenitip from "../penitip/ShowHistoryPenitip";
import CRUDPenitipan from "../penitip/CRUDPenitipan";
import PengambilanBarangKembali from "../penitip/PengambilanBarangKembali";


//Organisasi
import InputDataOrganisasi from "../organisasi/InputDataOrganisasi";
import CRUDTransaksiRequestDonasi from "../organisasi/CRUDTransaksiRequestDonasi";

//NOTIFIKASI
// import ShowPenjualanBulanan from "../Owner/ShowPenjualanBulanan";
// import ShowKomisiBulanaPerProduk from "../Owner/ShowKomisiBulananPerProduk";
// import ShowStokGudang from "../Owner/ShowStokGudang";
// import ShowPenjualanPerKategoriBarang from "../Owner/ShowPenjualanPerKategoriBarang";
// import ShowMasaPenitipHabis from "../Owner/ShowMasaPenitipHabis";
// import ShowDonasiBarang from "../Owner/ShowDonasiBarang";
// import ShowRequestDonasi from "../Owner/ShowRequestDonasi";
// import ShowTransaksiPenitip from "../Owner/ShowTransaksiPenitip";

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
      element: <Register />,
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
          <CRUDPenitip />
          <Footer />
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
          <CRUDPegawai />
          <Footer />
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
    // {
    //   path: "/admin/customer",
    //   element: (
    //     <div>
    //       <ShowAdmin />
    //       <Footer />
    //     </div>
    //   ),
    // },
  
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
          <NavbarPage />
          <PengambilanBarangKembali />
          <Footer />
        </div>
      ),
    },
  
    // ORGANISASI
    {
      path: "/organisasi/input",
      element: (
        <div>
          <NavbarPage />
          <InputDataOrganisasi />
          <Footer />
        </div>
      ),
    },
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
  
    // NOTIFIKASI
//     {
//       path: "/owner/penjualan-bulanan",
//       element: (
//         <div>
//           <NavbarPage />
//           <ShowPenjualanBulanan />
//           <Footer />
//         </div>
//       ),
//     },
//     {
//       path: "/owner/komisi-produk",
//       element: (
//         <div>
//           <NavbarPage />
//           <ShowKomisiBulanaPerProduk />
//           <Footer />
//         </div>
//       ),
//     },
//     {
//       path: "/owner/stok-gudang",
//       element: (
//         <div>
//           <NavbarPage />
//           <ShowStokGudang />
//           <Footer />
//         </div>
//       ),
//     },
//     {
//       path: "/owner/penjualan-kategori",
//       element: (
//         <div>
//           <NavbarPage />
//           <ShowPenjualanPerKategoriBarang />
//           <Footer />
//         </div>
//       ),
//     },
//     {
//       path: "/owner/masa-habis",
//       element: (
//         <div>
//           <NavbarPage />
//           <ShowMasaPenitipHabis />
//           <Footer />
//         </div>
//       ),
//     },
//     {
//       path: "/owner/donasi-barang",
//       element: (
//         <div>
//           <NavbarPage />
//           <ShowDonasiBarang />
//           <Footer />
//         </div>
//       ),
//     },
//     {
//       path: "/owner/request-donasi",
//       element: (
//         <div>
//           <NavbarPage />
//           <ShowRequestDonasi />
//           <Footer />
//         </div>
//       ),
//     },
//     {
//       path: "/owner/transaksi-penitip",
//       element: (
//         <div>
//           <NavbarPage />
//           <ShowTransaksiPenitip />
//           <Footer />
//         </div>
//       ),
//     },

{
  path: "*",
  element: <div>Routes Not Found!</div>,
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
