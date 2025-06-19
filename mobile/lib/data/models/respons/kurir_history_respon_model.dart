// To parse this JSON data, do
//
//     final kurirHistoryResponModel = kurirHistoryResponModelFromJson(jsonString);

import 'dart:convert';

KurirHistoryResponModel kurirHistoryResponModelFromJson(String str) => KurirHistoryResponModel.fromJson(json.decode(str));

String kurirHistoryResponModelToJson(KurirHistoryResponModel data) => json.encode(data.toJson());

class KurirHistoryResponModel {
    String? message;
    List<HistoryDatum>? data;

    KurirHistoryResponModel({
        this.message,
        this.data,
    });

    factory KurirHistoryResponModel.fromJson(Map<String, dynamic> json) => KurirHistoryResponModel(
        message: json["message"],
        data: json["data"] == null ? [] : List<HistoryDatum>.from(json["data"]!.map((x) => HistoryDatum.fromJson(x))),
    );

    Map<String, dynamic> toJson() => {
        "message": message,
        "data": data == null ? [] : List<dynamic>.from(data!.map((x) => x.toJson())),
    };
}

class HistoryDatum {
    String? namaPembeli;
    String? alamatPembeli;
    int? biayaPengiriman;
    String? catatan;

    HistoryDatum({
        this.namaPembeli,
        this.alamatPembeli,
        this.biayaPengiriman,
        this.catatan,
    });

    factory HistoryDatum.fromJson(Map<String, dynamic> json) => HistoryDatum(
        namaPembeli: json["nama_pembeli"],
        alamatPembeli: json["alamat_pembeli"],
        biayaPengiriman: json["biaya_pengiriman"],
        catatan: json["catatan"],
    );

    Map<String, dynamic> toJson() => {
        "nama_pembeli": namaPembeli,
        "alamat_pembeli": alamatPembeli,
        "biaya_pengiriman": biayaPengiriman,
        "catatan": catatan,
    };
}
