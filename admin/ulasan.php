<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Buku</th>
            <th>Peminjam</th>
            <th>Ulasan</th>
            <th>Rating</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        $query = mysqli_query($koneksi, "SELECT * FROM ulasanbuku 
                                         LEFT JOIN user ON ulasanbuku.UserID = user.UserID 
                                         LEFT JOIN buku ON ulasanbuku.BukuID = buku.BukuID");
        while($row = mysqli_fetch_array($query)){
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $row['Judul']; ?></td>
                <td><?php echo $row['NamaLengkap']; ?></td>
                <td><?php echo $row['Ulasan']; ?></td>
                <td><span class="badge bg-success"><?php echo $row['Rating']; ?></span></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>