<form action="{{ route('parking.enter') }}" method="POST">
    @csrf
    <label>Pilih Lokasi:</label>
    <select name="id_lokasi" required>
        <option value="1">Gedung A</option> 
    </select>
    
    <label>Pilih Jenis Kendaraan:</label>
    <select name="id_jenis" required>
        <option value="1">Motorcycle</option>
    </select>
    
    <button type="submit">Enter Vehicle</button>
</form>

<hr>

<form action="{{ route('parking.exit') }}" method="POST">
    @csrf
    <label>Nomor Tiket:</label>
    <input type="text" name="no_tiket" required placeholder="TKT-XXX...">
    
    <label>Nomor Polisi:</label>
    <input type="text" name="no_polisi" required placeholder="B 1234 ABC">
    
    <button type="submit">Exit Vehicle</button>
</form>