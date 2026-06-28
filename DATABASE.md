# Database Summary

## Main Tables

- `users`: identity, contact data, and role (`admin`, `relawan`, `donatur`).
- `program_donasi`: public fundraising programs and donation totals.
- `donasi`: donor payment simulation records.
- `pembelian_pangan`: admin food purchase records.
- `stok_pangan`: inventory batches with canonical `jumlah_awal` and `jumlah_tersedia`.
- `riwayat_stok_pangan`: inventory movement history.
- `sorting`: volunteer sorting work.
- `packaging`: volunteer packaging work.
- `penerima_bantuan`: beneficiaries with optional coordinates.
- `distribusi`: assigned distribution tasks.
- `distribusi_stok_pangan`: FEFO allocation log per stock batch.
- `simulasi_gudang`: bottleneck simulation history.

## FEFO Rule

Distribution allocation orders inventory by `tanggal_kadaluarsa`, then `tanggal_masuk`, then `id`. Allocations reduce only `stok_pangan.jumlah_tersedia` and create both pivot allocation rows and inventory history rows.

