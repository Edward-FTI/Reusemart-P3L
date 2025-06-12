import 'dart:math';

import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:mobile/data/models/hunter/HistoryHunterModel.dart';
import 'package:mobile/data/datasource/Hunter/historyHunter_data_source.dart';

class HistoryHunter extends StatefulWidget {
  const HistoryHunter({super.key});

  @override
  State<HistoryHunter> createState() => _HistoryHunterState();
}

class _HistoryHunterState extends State<HistoryHunter> {
  List<HistoryHunterModel>? historyList;

  @override
  void initState() {
    super.initState();
    // loadHistory();
  }

  // Future<void> loadHistory() async {
  //   final response = await HunterDataSource.getHistoryHunteran();
  //   if (response != null) {
  //     log("History Hunter berhasil dimuat: ${data.toString()}");
  //     setState(() {
  //       historyList = response.data;
  //     });
  //   }
  // }

  String formatCurrency(int? value) {
    return NumberFormat.currency(locale: 'id_ID', symbol: 'Rp', decimalDigits: 0)
        .format(value ?? 0);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("History Barang Hunter"),
        centerTitle: true,
        backgroundColor: Colors.blue,
      ),
      body: historyList == null
          ? const Center(child: CircularProgressIndicator())
          : historyList!.isEmpty
              ? const Center(child: Text("Tidak ada data."))
              : ListView.builder(
                  padding: const EdgeInsets.all(12),
                  itemCount: historyList!.length,
                  itemBuilder: (context, index) {
                    final item = historyList![index];

                    return Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: item.detail.map((detail) {
                        final barang = detail.barang;
                        return Card(
                          margin: const EdgeInsets.symmetric(vertical: 8),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(12),
                          ),
                          elevation: 2,
                          child: Padding(
                            padding: const EdgeInsets.all(16),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  "Nama Barang: ${barang?.namaBarang ?? '-'}",
                                  style: const TextStyle(
                                    fontWeight: FontWeight.bold,
                                    fontSize: 16,
                                  ),
                                ),
                                const SizedBox(height: 6),
                                Text(
                                  "Kategori: ${barang?.idKategori ?? '-'}",
                                  style: const TextStyle(fontSize: 14),
                                ),
                                const SizedBox(height: 6),
                                Text(
                                  "Harga: ${formatCurrency(barang?.hargaBarang)}",
                                  style: const TextStyle(fontSize: 14),
                                ),
                              ],
                            ),
                          ),
                        );
                      }).toList(),
                    );
                  },
                ),
    );
  }
}
