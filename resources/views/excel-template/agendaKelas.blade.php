<table border="1">
    <thead>
        <tr>
            <th colspan="8">Informasi Agenda Kelas</th>
            <th colspan="5">Siswa Absen</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <!-- <th>ID</th> -->
            <th>Tanggal Agenda Kelas</th>
            <th>Nama Rombel</th>
            <th>Jurusan</th>
            <th>Nama Guru</th>
            <th>NUPTK</th>
            <th>Mata Pelajaran</th>
            <th>Materi Pembelajaran</th>
            <th>Kehadiran Guru</th>
            <th>Nama Siswa</th>
            <th>NISN</th>
            <th>Jenis Kelamin</th>
            <th>Keterangan Absen</th>
            <th>Alasan</th>
        </tr>
        @foreach($data as $item)
            @foreach($item->siswa_absens as $index => $absen)
                <tr>
                    @if($index === 0)
                    <!-- <td rowspan="{{ count($item->siswa_absens) }}">{{ $item->id }}</td> -->
                    <td rowspan="{{ count($item->siswa_absens) }}">{{ \Carbon\Carbon::parse($item->tanggal_agenda_kelas)->locale('id')->isoFormat('dddd, D MMMM Y H:mm') }}</td>
                    <td rowspan="{{ count($item->siswa_absens) }}">{{ $item->nama_rombel }}</td>
                    <td rowspan="{{ count($item->siswa_absens) }}">{{ $item->jurusan }}</td>
                    <td rowspan="{{ count($item->siswa_absens) }}">{{ $item->nama_guru }}</td>
                    <td rowspan="{{ count($item->siswa_absens) }}">{{ $item->nuptk }}</td>
                    <td rowspan="{{ count($item->siswa_absens) }}">{{ $item->mata_pelajaran }}</td>
                    <td rowspan="{{ count($item->siswa_absens) }}">{{ $item->materi_pembelajaran }}</td>
                    <td rowspan="{{ count($item->siswa_absens) }}">{{ $item->kehadiran_guru }}</td>
                    @endif
                    @if (count($item->siswa_absens) !== 0)
                    <td>{{ $absen->nama_lengkap }}</td>
                    <td>{{ $absen->nisn }}</td>
                    <td>{{ $absen->jenis_kelamin }}</td>
                    <td>{{ $absen->keterangan_absen }}</td>
                    <td>{{ $absen->alasan }}</td>
                    @endif
                </tr>
            @endforeach
        @endforeach

        @foreach($data as $item)
            @if(count($item->siswa_absens) === 0)
            <tr>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_agenda_kelas)->locale('id')->isoFormat('dddd, D MMMM Y H:mm') }}</td>
                <td>{{ $item->nama_rombel }}</td>
                <td>{{ $item->jurusan }}</td>
                <td>{{ $item->nama_guru }}</td>
                <td>{{ $item->nuptk }}</td>
                <td>{{ $item->mata_pelajaran }}</td>
                <td>{{ $item->materi_pembelajaran }}</td>
                <td>{{ $item->kehadiran_guru }}</td>
                <td colspan="5">Tidak Ada Siswa Absen</td>
            </tr>
            @endif
        @endforeach
        
    </tbody>
</table>