// To parse this JSON data, do
//
//     final kurirResponModel = kurirResponModelFromJson(jsonString);

import 'dart:convert';

KurirResponModel kurirResponModelFromJson(String str) => KurirResponModel.fromJson(json.decode(str));

String kurirResponModelToJson(KurirResponModel data) => json.encode(data.toJson());

class KurirResponModel {
    String? nama;
    String? email;
    DateTime? tglLahir;
    int? gaji;
    String? jabatan;

    KurirResponModel({
        this.nama,
        this.email,
        this.tglLahir,
        this.gaji,
        this.jabatan,
    });

    factory KurirResponModel.fromJson(Map<String, dynamic> json) => KurirResponModel(
        nama: json["nama"],
        email: json["email"],
        tglLahir: json["tgl_lahir"] == null ? null : DateTime.parse(json["tgl_lahir"]),
        gaji: json["gaji"],
        jabatan: json["jabatan"],
    );

    Map<String, dynamic> toJson() => {
        "nama": nama,
        "email": email,
        "tgl_lahir": "${tglLahir!.year.toString().padLeft(4, '0')}-${tglLahir!.month.toString().padLeft(2, '0')}-${tglLahir!.day.toString().padLeft(2, '0')}",
        "gaji": gaji,
        "jabatan": jabatan,
    };
}
