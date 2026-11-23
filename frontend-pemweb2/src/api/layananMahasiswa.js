import klien from './klien'
import { backend } from './konfigurasiBackend'

export async function ambilDaftarMahasiswa(parameter) {
  const respons = await klien.get('/mahasiswa', {
    params: {
      [backend.parameterHalaman]: parameter.halaman,
      cari: parameter.cari,
      per_halaman: parameter.perHalaman,
    },
  })
  return {
    data: respons.data.data,
    meta: backend.bacaMeta(respons.data.meta),
  }
}
export async function ambilDetailMahasiswa(id) {
const respons = await klien.get(`/mahasiswa/${id}`)
return respons.data
}
export async function simpanMahasiswa(data) {
const respons = await klien.post('/mahasiswa', data)
return respons.data
}
export async function hapusMahasiswa(id) {
const respons = await klien.delete(`/mahasiswa/${id}`)
return respons.data
}
