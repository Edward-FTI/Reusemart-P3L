import 'dart:developer';
import 'package:flutter/material.dart';
import 'package:flashy_tab_bar2/flashy_tab_bar2.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:intl/intl.dart';

import 'package:mobile/data/datasource/kurir/kurir_data_source.dart';
import 'package:mobile/data/datasource/kurir/kurir_history_data_source.dart';
import 'package:mobile/data/datasource/kurir/kurir_tugas_data_source.dart';
import 'package:mobile/data/datasource/kurir/kurir_selesai_data_source.dart';

import 'package:mobile/data/models/respons/kurir_respon_model.dart';
import 'package:mobile/data/models/respons/kurir_history_respon_model.dart';
import 'package:mobile/data/models/respons/kurir_tugas_respon_model.dart';
import 'package:mobile/data/models/respons/kurir_selesai_respon_model.dart';

class MainKurirPage extends StatefulWidget {
  const MainKurirPage({super.key});

  @override
  State<MainKurirPage> createState() => _MainKurirPageState();
}

class _MainKurirPageState extends State<MainKurirPage> {
  int _selectedIndex = 0;

  final List<Widget> _pages = const [
    ProfileKurirPage(),
    HistoryKurirPage(),
    TugasKurirPage(),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: _pages[_selectedIndex],
      bottomNavigationBar: FlashyTabBar(
        selectedIndex: _selectedIndex,
        showElevation: true,
        onItemSelected: (index) => setState(() => _selectedIndex = index),
        items: [
          FlashyTabBarItem(icon: Icon(Icons.person), title: Text('Profil')),
          FlashyTabBarItem(icon: Icon(Icons.history), title: Text('History')),
          FlashyTabBarItem(icon: Icon(Icons.task), title: Text('Tugas')),
        ],
      ),
    );
  }
}

class ProfileKurirPage extends StatefulWidget {
  const ProfileKurirPage({super.key});

  @override
  State<ProfileKurirPage> createState() => _ProfileKurirPageState();
}

class _ProfileKurirPageState extends State<ProfileKurirPage> {
  KurirResponModel? kurir;

  @override
  void initState() {
    super.initState();
    loadKurirData();
  }

  Future<void> loadKurirData() async {
    final data = await KurirDataSource().getKurir();
    if (data != null) {
      log("Kurir berhasil dimuat: ${data.toJson()}");
      setState(() {
        kurir = data;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    if (kurir == null) {
      return const Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            SizedBox(width: 50, height: 50, child: CircularProgressIndicator()),
            Divider(height: 24, color: Colors.transparent),
            Text("Loading...",
                style: TextStyle(fontSize: 16, color: Colors.black54)),
          ],
        ),
      );
    }

    return Scaffold(
      appBar: AppBar(
        backgroundColor: Colors.blue,
        title: const Text("Profil Kurir"),
        centerTitle: true,
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            const SizedBox(height: 24),
            CircleAvatar(
              radius: 60,
              backgroundImage: NetworkImage(
                  'https://img.icons8.com/?size=100&id=NPW07SMh7Aco&format=png'),
              backgroundColor: Colors.grey[200],
            ),
            const SizedBox(height: 16),
            Text(kurir!.nama ?? 'Nama Kurir',
                style:
                    const TextStyle(fontSize: 22, fontWeight: FontWeight.bold)),
            const SizedBox(height: 8),
            Text(kurir!.email ?? 'Email',
                style: const TextStyle(fontSize: 16, color: Colors.grey)),
            const Divider(),
            buildInfoRow(
                "Tanggal Lahir",
                kurir!.tglLahir != null
                    ? "${kurir!.tglLahir!.day}/${kurir!.tglLahir!.month}/${kurir!.tglLahir!.year}"
                    : "-"),
            buildInfoRow(
                "Gaji", kurir!.gaji != null ? "Rp${kurir!.gaji}" : "-"),
            buildInfoRow("Jabatan", kurir!.jabatan ?? "-"),
            const SizedBox(height: 20),
          ],
        ),
      ),
    );
  }

  Widget buildInfoRow(String label, String value) {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
      decoration: BoxDecoration(
        color: Colors.grey[100],
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: Colors.grey.shade300),
      ),
      child: Row(
        children: [
          SizedBox(
            width: 120,
            child: Text(label,
                style:
                    const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
          ),
          const SizedBox(width: 10),
          Expanded(child: Text(value, style: const TextStyle(fontSize: 16))),
        ],
      ),
    );
  }
}

class HistoryKurirPage extends StatefulWidget {
  const HistoryKurirPage({super.key});

  @override
  State<HistoryKurirPage> createState() => _HistoryKurirPageState();
}

class _HistoryKurirPageState extends State<HistoryKurirPage> {
  List<HistoryDatum>? historyList;

  @override
  void initState() {
    super.initState();
    loadKurirHistory();
  }

  Future<void> loadKurirHistory() async {
    final response = await KurirHistoryDataSource().getKurirHistory();
    if (response != null) {
      log("History kurir berhasil dimuat: ${response.toJson()}");
      setState(() {
        historyList = response.data;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    if (historyList == null) {
      return const Center(
        child: CircularProgressIndicator(),
      );
    }

    if (historyList!.isEmpty) {
      return const Center(child: Text("Tidak ada data pengiriman."));
    }

    return Scaffold(
      appBar: AppBar(
        backgroundColor: Colors.blue,
        title: const Text("History Pengiriman"),
        centerTitle: true,
      ),
      body: ListView.builder(
        itemCount: historyList!.length,
        itemBuilder: (context, index) {
          final item = historyList![index];
          return Padding(
            padding: const EdgeInsets.symmetric(vertical: 8, horizontal: 12),
            child: Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: const Color(0xFFF3F3F3),
                borderRadius: BorderRadius.circular(8),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(item.namaPembeli ?? 'Nama pembeli tidak tersedia',
                      style: const TextStyle(
                          fontSize: 16, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 4),
                  Text(item.alamatPembeli ?? '-'),
                  const SizedBox(height: 4),
                  Text("Biaya Pengiriman: Rp${item.biayaPengiriman ?? 0}"),
                  const SizedBox(height: 4),
                  Text("Catatan: ${item.catatan ?? '-'}",
                      style: const TextStyle(
                          fontSize: 13,
                          color: Colors.grey,
                          fontStyle: FontStyle.italic)),
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}

class TugasKurirPage extends StatefulWidget {
  const TugasKurirPage({super.key});

  @override
  State<TugasKurirPage> createState() => _TugasKurirPageState();
}

class _TugasKurirPageState extends State<TugasKurirPage> {
  late Future<KurirTugasResponModel?> _kurirTugasFuture;

  @override
  void initState() {
    super.initState();
    _kurirTugasFuture = KurirTugasDataSource().getKurirTugas();
  }

  String formatDateTime(dynamic dateTimeString) {
    if (dateTimeString == null || dateTimeString.toString().isEmpty) return "-";
    try {
      final dateTime = DateTime.parse(dateTimeString);
      return DateFormat('dd MMMM yyyy, HH:mm', 'id_ID').format(dateTime);
    } catch (_) {
      return dateTimeString.toString();
    }
  }

  void _showKonfirmasiPopup(int? idTugas) {
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        title: const Text("Konfirmasi"),
        content:
            const Text("Apakah Anda yakin ingin menyelesaikan pengiriman ini?"),
        actions: [
          TextButton(
            onPressed: () => Navigator.of(context).pop(),
            child: const Text("Batal"),
          ),
          TextButton(
            onPressed: () async {
              Navigator.of(context).pop();
              if (idTugas == null) {
                Fluttertoast.showToast(msg: "ID tugas tidak ditemukan");
                return;
              }

              final result =
                  await KurirSelesaiDataSource().selesaikanPengiriman(idTugas);
              if (result != null &&
                  result.message == "Pengiriman berhasil diselesaikan") {
                Fluttertoast.showToast(msg: result.message!);
                setState(() {
                  _kurirTugasFuture = KurirTugasDataSource().getKurirTugas();
                });
              } else {
                Fluttertoast.showToast(
                    msg: result?.message ?? "Gagal menyelesaikan pengiriman");
              }
            },
            child: const Text("Ya"),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: Colors.blue,
        title: const Text("Tugas Pengiriman"),
        centerTitle: true,
      ),
      body: FutureBuilder<KurirTugasResponModel?>(
        future: _kurirTugasFuture,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          } else if (!snapshot.hasData ||
              snapshot.data!.data == null ||
              snapshot.data!.data!.isEmpty) {
            return const Center(child: Text("Tidak ada tugas pengiriman"));
          }

          final tugasList = snapshot.data!.data!;
          return ListView.builder(
            itemCount: tugasList.length,
            itemBuilder: (context, index) {
              final tugas = tugasList[index];
              return Padding(
                padding:
                    const EdgeInsets.symmetric(vertical: 8, horizontal: 12),
                child: Container(
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: const Color(0xFFF3F3F3),
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(tugas.namaPembeli ?? '-',
                          style: const TextStyle(fontWeight: FontWeight.bold)),
                      const SizedBox(height: 4),
                      Text(tugas.alamatPembeli ?? '-'),
                      const SizedBox(height: 4),
                      Text("Biaya Pengiriman: Rp${tugas.biayaPengiriman ?? 0}"),
                      const SizedBox(height: 4),
                      Text(
                          "Tanggal Pengiriman: ${formatDateTime(tugas.tglPengiriman)}",
                          style: const TextStyle(
                              fontStyle: FontStyle.italic, color: Colors.grey)),
                      const SizedBox(height: 10),
                      Align(
                        alignment: Alignment.centerRight,
                        child: ElevatedButton.icon(
                          onPressed: () =>
                              _showKonfirmasiPopup(tugas.idTransaksiPengiriman),
                          icon: const Icon(Icons.check),
                          label: const Text("Selesaikan"),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.green,
                            foregroundColor: Colors.white,
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              );
            },
          );
        },
      ),
    );
  }
}
