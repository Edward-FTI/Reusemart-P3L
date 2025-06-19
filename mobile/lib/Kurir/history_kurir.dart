// import 'package:flutter/material.dart';
// import 'dart:developer';
// import 'package:mobile/data/models/respons/kurir_history_respon_model.dart';
// import 'package:mobile/data/datasource/kurir/kurir_history_data_source.dart';

// class HistoryKurirPage extends StatefulWidget {
//   const HistoryKurirPage({super.key});

//   @override
//   State<HistoryKurirPage> createState() => _HistoryKurirPageState();
// }

// class _HistoryKurirPageState extends State<HistoryKurirPage> {
//   List<Datum>? historyList;

//   @override
//   void initState() {
//     super.initState();
//     loadKurirHistory();
//   }

//   loadKurirHistory() async {
//     final response = await KurirHistoryDataSource().getKurirHistory();

//     if (response != null) {
//       log("History kurir berhasil dimuat: ${response.toJson()}");
//       setState(() {
//         historyList = response.data;
//       });
//     } else {
//       log("History kurir == null, tidak bisa dimuat");
//     }
//   }

//   @override
//   Widget build(BuildContext context) {
//     if (historyList == null) {
//       return Scaffold(
//         body: Center(
//           child: Column(
//             mainAxisAlignment: MainAxisAlignment.center,
//             children: const [
//               SizedBox(
//                 width: 50,
//                 height: 50,
//                 child: CircularProgressIndicator(),
//               ),
//               Divider(
//                 color: Colors.transparent,
//                 height: 24,
//               ),
//               Text(
//                 "Memuat data history pengiriman...",
//                 style: TextStyle(fontSize: 15, color: Colors.black54),
//               )
//             ],
//           ),
//         ),
//       );
//     }

//     if (historyList!.isEmpty) {
//       return Scaffold(
//         appBar: AppBar(title: const Text("History Pengiriman")),
//         body: const Center(
//           child: Text("Tidak ada data pengiriman."),
//         ),
//       );
//     }

//     return Scaffold(
//       appBar: AppBar(
//         title: const Text("History Pengiriman"),
//         centerTitle: true,
//         backgroundColor: Colors.blue,
//       ),
//       body: ListView.builder(
//         itemCount: historyList!.length,
//         itemBuilder: (context, index) {
//           final item = historyList![index];
//           return Padding(
//             padding: const EdgeInsets.symmetric(vertical: 8.0, horizontal: 12.0),
//             child: Container(
//               padding: const EdgeInsets.all(12),
//               decoration: BoxDecoration(
//                 color: const Color(0xFFF3F3F3),
//                 borderRadius: BorderRadius.circular(8),
//               ),
//               child: Column(
//                 crossAxisAlignment: CrossAxisAlignment.start,
//                 children: [
//                   Text(
//                     item.namaPembeli ?? 'Nama pembeli tidak tersedia',
//                     style: const TextStyle(
//                       fontSize: 16,
//                       fontWeight: FontWeight.bold,
//                     ),
//                   ),
//                   const SizedBox(height: 4),
//                   Text(
//                     item.alamatPembeli ?? '-',
//                     style: const TextStyle(
//                       fontSize: 14,
//                       color: Colors.black87,
//                     ),
//                   ),
//                   const SizedBox(height: 4),
//                   Text(
//                     "Biaya Pengiriman: Rp${item.biayaPengiriman ?? 0}",
//                     style: const TextStyle(
//                       fontSize: 14,
//                       color: Colors.black54,
//                     ),
//                   ),
//                   const SizedBox(height: 4),
//                   Text(
//                     "Catatan: ${item.catatan ?? '-'}",
//                     style: const TextStyle(
//                       fontSize: 13,
//                       color: Colors.grey,
//                       fontStyle: FontStyle.italic,
//                     ),
//                   ),
//                 ],
//               ),
//             ),
//           );
//         },
//       ),
//     );
//   }
// }
