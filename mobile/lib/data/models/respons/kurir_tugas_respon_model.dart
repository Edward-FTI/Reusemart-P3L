// To parse this JSON data, do
//
//     final kurirTugasResponModel = kurirTugasResponModelFromJson(jsonString);

import 'dart:convert';

KurirTugasResponModel kurirTugasResponModelFromJson(String str) => KurirTugasResponModel.fromJson(json.decode(str));

String kurirTugasResponModelToJson(KurirTugasResponModel data) => json.encode(data.toJson());

class KurirTugasResponModel {
    String? message;
    List<TugasDatum>? data;

    KurirTugasResponModel({
        this.message,
        this.data,
    });

    factory KurirTugasResponModel.fromJson(Map<String, dynamic> json) => KurirTugasResponModel(
        message: json["message"],
        data: json["data"] == null ? [] : List<TugasDatum>.from(json["data"]!.map((x) => TugasDatum.fromJson(x))),
    );

    Map<String, dynamic> toJson() => {
        "message": message,
        "data": data == null ? [] : List<dynamic>.from(data!.map((x) => x.toJson())),
    };
}

class TugasDatum {
    int? idTransaksiPengiriman;
    String? namaPembeli;
    String? alamatPembeli;
    int? biayaPengiriman;
    dynamic tglPengiriman;

    TugasDatum({
        this.idTransaksiPengiriman,
        this.namaPembeli,
        this.alamatPembeli,
        this.biayaPengiriman,
        this.tglPengiriman,
    });

    factory TugasDatum.fromJson(Map<String, dynamic> json) => TugasDatum(
        idTransaksiPengiriman: json["id_transaksi_pengiriman"],
        namaPembeli: json["nama_pembeli"],
        alamatPembeli: json["alamat_pembeli"],
        biayaPengiriman: json["biaya_pengiriman"],
        tglPengiriman: json["tgl_pengiriman"],
    );

    Map<String, dynamic> toJson() => {
        "id_transaksi_pengiriman": idTransaksiPengiriman,
        "nama_pembeli": namaPembeli,
        "alamat_pembeli": alamatPembeli,
        "biaya_pengiriman": biayaPengiriman,
        "tgl_pengiriman": tglPengiriman,
    };
}
