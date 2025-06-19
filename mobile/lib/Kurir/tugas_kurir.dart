// import 'package:flutter/material.dart';
// import 'package:fluttertoast/fluttertoast.dart';
// import 'package:intl/intl.dart';
// import 'package:mobile/data/models/respons/kurir_tugas_respon_model.dart';
// import 'package:mobile/data/models/respons/kurir_selesai_respon_model.dart';
// import 'package:mobile/data/datasource/kurir/kurir_tugas_data_source.dart';
// import 'package:mobile/data/datasource/kurir/kurir_selesai_data_source.dart';

// class TugasKurirPage extends StatefulWidget {
//   const TugasKurirPage({super.key});

//   @override
//   State<TugasKurirPage> createState() => _TugasKurirPageState();
// }

// class _TugasKurirPageState extends State<TugasKurirPage> {
//   late Future<KurirTugasResponModel?> _kurirTugasFuture;

//   @override
//   void initState() {
//     super.initState();
//     _kurirTugasFuture = KurirTugasDataSource().getKurirTugas();
//   }

//   String formatDateTime(dynamic dateTimeString) {
//     if (dateTimeString == null || dateTimeString.toString().isEmpty) {
//       return "-";
//     }
//     try {
//       DateTime dateTime = DateTime.parse(dateTimeString);
//       return DateFormat('dd MMMM yyyy, HH:mm', 'id_ID').format(dateTime);
//     } catch (e) {
//       return dateTimeString.toString(); // fallback jika gagal parse
//     }
//   }

//   void _showKonfirmasiPopup(int? idTugas) {
//     showDialog(
//       context: context,
//       builder: (context) {
//         return AlertDialog(
//           title: const Text("Konfirmasi"),
//           content: const Text("Apakah Anda yakin ingin menyelesaikan pengiriman ini?"),
//           actions: [
//             TextButton(
//               onPressed: () => Navigator.of(context).pop(),
//               child: const Text("Batal"),
//             ),
//             TextButton(
//               onPressed: () async {
//                 Navigator.of(context).pop();
//                 if (idTugas == null) {
//                   Fluttertoast.showToast(msg: "ID tugas tidak ditemukan");
//                   return;
//                 }

//                 KurirSelesaiResponModel? result =
//                     await KurirSelesaiDataSource().selesaikanPengiriman(idTugas);

//                 if (result != null &&
//                     result.message == "Pengiriman berhasil diselesaikan") {
//                   Fluttertoast.showToast(msg: result.message!);
//                   setState(() {
//                     _kurirTugasFuture = KurirTugasDataSource().getKurirTugas();
//                   });
//                 } else {
//                   Fluttertoast.showToast(
//                       msg: result?.message ?? "Gagal menyelesaikan pengiriman");
//                 }
//               },
//               child: const Text("Ya"),
//             ),
//           ],
//         );
//       },
//     );
//   }

//   @override
//   Widget build(BuildContext context) {
//     return Scaffold(
//       appBar: AppBar(
//         title: const Text("Tugas Pengiriman"),
//         centerTitle: true,
//         backgroundColor: Colors.blue,
//       ),
//       body: FutureBuilder<KurirTugasResponModel?>(
//         future: _kurirTugasFuture,
//         builder: (context, snapshot) {
//           if (snapshot.connectionState == ConnectionState.waiting) {
//             return const Center(child: CircularProgressIndicator());
//           } else if (snapshot.hasError) {
//             return Center(child: Text("Terjadi kesalahan: ${snapshot.error}"));
//           } else if (!snapshot.hasData ||
//               snapshot.data!.data == null ||
//               snapshot.data!.data!.isEmpty) {
//             return const Center(child: Text("Tidak ada tugas pengiriman"));
//           } else {
//             List<Datum> tugasList = snapshot.data!.data!;
//             return ListView.builder(
//               itemCount: tugasList.length,
//               itemBuilder: (context, index) {
//                 final tugas = tugasList[index];
//                 return Padding(
//                   padding: const EdgeInsets.symmetric(vertical: 8.0, horizontal: 12.0),
//                   child: Container(
//                     padding: const EdgeInsets.all(12),
//                     decoration: BoxDecoration(
//                       color: const Color(0xFFF3F3F3),
//                       borderRadius: BorderRadius.circular(8),
//                     ),
//                     child: Column(
//                       crossAxisAlignment: CrossAxisAlignment.start,
//                       children: [
//                         Text(
//                           tugas.namaPembeli ?? 'Nama pembeli tidak tersedia',
//                           style: const TextStyle(
//                             fontSize: 16,
//                             fontWeight: FontWeight.bold,
//                           ),
//                         ),
//                         const SizedBox(height: 4),
//                         Text(
//                           tugas.alamatPembeli ?? '-',
//                           style: const TextStyle(
//                             fontSize: 14,
//                             color: Colors.black87,
//                           ),
//                         ),
//                         const SizedBox(height: 4),
//                         Text(
//                           "Biaya Pengiriman: Rp${tugas.biayaPengiriman ?? 0}",
//                           style: const TextStyle(
//                             fontSize: 14,
//                             color: Colors.black54,
//                           ),
//                         ),
//                         const SizedBox(height: 4),
//                         Text(
//                           "Tanggal Pengiriman: ${formatDateTime(tugas.tglPengiriman)}",
//                           style: const TextStyle(
//                             fontSize: 13,
//                             color: Colors.grey,
//                             fontStyle: FontStyle.italic,
//                           ),
//                         ),
//                         const SizedBox(height: 10),
//                         Align(
//                           alignment: Alignment.centerRight,
//                           child: ElevatedButton.icon(
//                             onPressed: () =>
//                                 _showKonfirmasiPopup(tugas.idTransaksiPengiriman),
//                             icon: const Icon(Icons.check),
//                             label: const Text("Selesaikan"),
//                             style: ElevatedButton.styleFrom(
//                               backgroundColor: Colors.green,
//                               foregroundColor: Colors.white,
//                             ),
//                           ),
//                         )
//                       ],
//                     ),
//                   ),
//                 );
//               },
//             );
//           }
//         },
//       ),
//     );
//   }
// }
