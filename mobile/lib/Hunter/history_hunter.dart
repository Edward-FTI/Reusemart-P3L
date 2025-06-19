import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'dart:developer';
import 'package:mobile/data/models/hunter/HistoryHunterModel.dart';
import 'package:mobile/data/datasource/Hunter/historyHunter_data_source.dart';

class HistoryHunter extends StatefulWidget {
  const HistoryHunter({super.key});

  @override
  State<HistoryHunter> createState() => _HistoryHunterState();
}

class _HistoryHunterState extends State<HistoryHunter> {
  List<Datum> historyList = [];
  int selectedMonth = DateTime.now().month;

  @override
  void initState() {
    super.initState();
    // loadHistory();
  }

// Future<void> loadHistory() async {
//   final response = await HunterHistoryDataSource.getHistoryHunteran();

//   if (response != null && response.data != null) {
//     setState(() {
//       historyList = response.data!;
//     });
//   } else {
//     log('Tidak ada data history.');
//   }
// }




  String formatCurrency(num? value) {
    return NumberFormat.currency(
      locale: 'id_ID',
      symbol: 'Rp',
      decimalDigits: 0,
    ).format(value ?? 0);
  }

  List<DropdownMenuItem<int>> getMonthDropdownItems() {
    return List.generate(12, (index) {
      return DropdownMenuItem(
        value: index + 1,
        child: Text(
          DateFormat.MMMM('id_ID').format(DateTime(0, index + 1)),
        ),
      );
    });
  }

  @override
  Widget build(BuildContext context) {
    final filteredList = historyList.where((item) {
      return item.detailTransaksi?.any((detail) {
            final tglMasuk = detail.barang?.tglPenitipan;
            return tglMasuk != null && tglMasuk.month == selectedMonth;
          }) ??
          false;
    }).toList();

    return Scaffold(
      appBar: AppBar(
        title: const Text("Laporan Komisi Bulanan"),
        centerTitle: true,
        backgroundColor: Colors.blue,
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(12),
            child: Row(
              children: [
                const Text("Pilih Bulan: "),
                const SizedBox(width: 10),
                DropdownButton<int>(
                  value: selectedMonth,
                  items: getMonthDropdownItems(),
                  onChanged: (value) {
                    if (value != null) {
                      setState(() => selectedMonth = value);
                    }
                  },
                ),
              ],
            ),
          ),
          Expanded(
            child: filteredList.isEmpty
                ? const Center(child: Text("Tidak ada data."))
                : ListView.builder(
                    padding: const EdgeInsets.all(12),
                    itemCount: filteredList.length,
                    itemBuilder: (context, index) {
                      final transaksi = filteredList[index];

                      return Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: transaksi.detailTransaksi!.map((detail) {
                          final barang = detail.barang;
                          final tglMasuk = barang?.tglPenitipan;
                          final tglLaku = transaksi.tglTransaksi;
                          final hargaJual = barang?.hargaBarang ?? 0;

                          int selisihHari = 0;
                          if (tglMasuk != null && tglLaku != null) {
                            selisihHari = tglLaku.difference(tglMasuk).inDays;
                          }

                          double persenKomisi =
                              (barang?.penambahanDurasi ?? 0) > 0 ? 0.3 : 0.2;
                          double komisiReuseMart = persenKomisi * hargaJual;
                          double komisiHunter = (barang?.idHunter != null)
                              ? 0.05 * hargaJual
                              : 0.0;
                          komisiReuseMart -= komisiHunter;

                          double bonusPenitip =
                              selisihHari < 7 ? 0.1 * (0.2 * hargaJual) : 0.0;
                          komisiReuseMart -= bonusPenitip;

                          return Card(
                            margin: const EdgeInsets.symmetric(vertical: 8),
                            shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(12)),
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
                                        fontSize: 16),
                                  ),
                                  const SizedBox(height: 6),
                                  Text(
                                      "Harga Jual: ${formatCurrency(hargaJual)}"),
                                  Text(
                                      "Tanggal Masuk: ${tglMasuk != null ? DateFormat('dd MMM yyyy', 'id_ID').format(tglMasuk) : '-'}"),
                                  Text(
                                      "Tanggal Laku: ${tglLaku != null ? DateFormat('dd MMM yyyy', 'id_ID').format(tglLaku) : '-'}"),
                                  Text(
                                      "Komisi Hunter: ${formatCurrency(komisiHunter)}"),
                                  Text(
                                      "Komisi Reuse Mart: ${formatCurrency(komisiReuseMart)}"),
                                  Text(
                                      "Bonus Penitip: ${formatCurrency(bonusPenitip)}"),
                                ],
                              ),
                            ),
                          );
                        }).toList(),
                      );
                    },
                  ),
          ),
        ],
      ),
    );
  }
}
