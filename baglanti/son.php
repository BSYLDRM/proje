<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("db.php"); // Veritabanı bağlantısını içeren dosya

// Çalışan ekleme
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["calisanEkle"])) {
        $adSoyad = $_POST["adSoyad"];

        // Çalışan ekleme sorgusu
        if (is_object($db)) {
            $sql = "INSERT INTO calisanlar (ad_soyad) VALUES (?)";
            $stmt = $db->prepare($sql);
        } else {
            die("Veritabanı bağlantısı hatası: \$db bir nesne değil.");
        }

        // Sorgu hazırlama başarısızsa hata göster
        if (!$stmt) {
            die("Sorgu hazırlama hatası: " . $db->error);
        }

        // Verileri sorguya bağla
        $stmt->bind_param("s", $adSoyad);

        // Sorguyu çalıştır
        $stmt->execute();

        // Çalışan ekleme başarısızsa hata göster
        if ($stmt->errno) {
            die("Çalışan ekleme hatası: " . $stmt->error);
        }

        // Başarılıysa mesaj göster
        if ($stmt->affected_rows > 0) {
            echo "Çalışan başarıyla eklendi.";
        } else {
            echo "Çalışan ekleme sırasında bir hata oluştu.";
        }

        // Bağlantıyı kapat
        $stmt->close();
    } elseif (isset($_POST["calisanSil"])) {
        $calisanId = $_POST["calisanId"];

        // Çalışan silme sorgusu
        if (is_object($db)) {
            $sql = "DELETE FROM calisanlar WHERE calisan_id = ?";
            $stmt = $db->prepare($sql);
        } else {
            die("Veritabanı bağlantısı hatası: \$db bir nesne değil.");
        }

        // Sorgu hazırlama başarısızsa hata göster
        if (!$stmt) {
            die("Sorgu hazırlama hatası: " . $db->error);
        }

        // Verileri sorguya bağla
        $stmt->bind_param("i", $calisanId);

        // Sorguyu çalıştır
        $stmt->execute();

        // Çalışan silme başarısızsa hata göster
        if ($stmt->errno) {
            die("Çalışan silme hatası: " . $stmt->error);
        }

        // Başarılıysa mesaj göster
        if ($stmt->affected_rows > 0) {
            echo "Çalışan başarıyla silindi.";
        } else {
            echo "Çalışan silme sırasında bir hata oluştu.";
        }

        // Bağlantıyı kapat
        $stmt->close();
    } elseif (isset($_POST["projeEkle"])) {
        $projeAdi = $_POST["projeAdi"];
        $baslangicTarihi = $_POST["baslangicTarihi"];
        $bitisTarihi = $_POST["bitisTarihi"];

        // Proje ekleme sorgusu
        if (is_object($db)) {
            $sql = "INSERT INTO projeler (proje_adi, baslangic_tarihi, bitis_tarihi) VALUES (?, ?, ?)";
            $stmt = $db->prepare($sql);
        } else {
            die("Veritabanı bağlantısı hatası: \$db bir nesne değil.");
        }

        // Sorgu hazırlama başarısızsa hata göster
        if (!$stmt) {
            die("Sorgu hazırlama hatası: " . $db->error);
        }

        // Verileri sorguya bağla
        $stmt->bind_param("sss", $projeAdi, $baslangicTarihi, $bitisTarihi);

        // Sorguyu çalıştır
        $stmt->execute();

        // Proje ekleme başarısızsa hata göster
        if ($stmt->errno) {
            die("Proje ekleme hatası: " . $stmt->error);
        }

        // Başarılıysa mesaj göster
        if ($stmt->affected_rows > 0) {
            echo "Proje başarıyla eklendi.";
        } else {
            echo "Proje ekleme sırasında bir hata oluştu.";
        }

        // Bağlantıyı kapat
        $stmt->close();
    } elseif (isset($_POST["projeSil"])) {
        $projeAdi = $_POST["projeAdi"];

        // Proje silme sorgusu
        if (is_object($db)) {
            $sql = "DELETE FROM projeler WHERE proje_adi = ?";
            $stmt = $db->prepare($sql);
        } else {
            die("Veritabanı bağlantısı hatası: \$db bir nesne değil.");
        }

        // Sorgu hazırlama başarısızsa hata göster
        if (!$stmt) {
            die("Sorgu hazırlama hatası: " . $db->error);
        }

        // Verileri sorguya bağla
        $stmt->bind_param("s", $projeAdi);

        // Sorguyu çalıştır
        $stmt->execute();

        // Proje silme başarısızsa hata göster
        if ($stmt->errno) {
            die("Proje silme hatası: " . $stmt->error);
        }

        // Başarılıysa mesaj göster
        if ($stmt->affected_rows > 0) {
            echo "Proje başarıyla silindi.";
        } else {
            echo "Proje silme sırasında bir hata oluştu.";
        }

        // Bağlantıyı kapat
        $stmt->close();
    }
}

// Çalışanları listele
$calisanlar = "";
$sql = "SELECT * FROM calisanlar";
$result = $db->query($sql);

if ($result->num_rows > 0) {
    // Her bir satırı çıktı olarak yazdır
    while ($row = $result->fetch_assoc()) {
        $calisanlar .= "Çalışan ID: " . $row["calisan_id"] . " - Ad Soyad: " . $row["ad_soyad"] . "<br>";
    }
} else {
    $calisanlar = "Hiç çalışan bulunamadı.";
}

// Projeleri listele
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gorev_adi = isset($_POST["gorev_adi"]) ? $_POST["gorev_adi"] : '';
    $baslangic_tarihi = isset($_POST["baslangic_tarihi"]) ? $_POST["baslangic_tarihi"] : '';
    $bitis_tarihi = isset($_POST["bitis_tarihi"]) ? $_POST["bitis_tarihi"] : '';
    $proje_id = isset($_POST["proje_id"]) ? intval($_POST["proje_id"]) : 0;
    $calisanlar = isset($_POST["calisanlar"]) ? $_POST["calisanlar"] : [];
    $gorev_id_to_delete = isset($_POST["gorev_id_to_delete"]) ? intval($_POST["gorev_id_to_delete"]) : 0;

    $checkSql = "SELECT * FROM projeler WHERE proje_id=?";
    $checkStmt = $db->prepare($checkSql);
    if ($checkStmt === false) {
        die("SQL sorgusu hazırlanırken hata: " . $db->error);
    }
    $checkStmt->bind_param("i", $proje_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $sql = "INSERT INTO gorevler (gorev_adi, baslangic_tarihi, bitis_tarihi, proje_id)
        VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        if ($stmt === false) {
            die("SQL sorgusu hazırlanırken hata: " . $db->error);
        }
        $stmt->bind_param("sssi", $gorev_adi, $baslangic_tarihi, $bitis_tarihi, $proje_id);
        if (!$stmt->execute()) {
            die("Görev eklerken hata: " . $stmt->error);
        }

        if ($stmt->affected_rows > 0) {
            $gorev_id = $stmt->insert_id;

            foreach ($calisanlar as $calisan_id) {
                $sql = "INSERT INTO gorevler_calisanlar (gorev_id, calisan_id) VALUES (?, ?)";
                $stmt2 = $db->prepare($sql);  
                if ($stmt2 === false) {
                    die("SQL sorgusu hazırlanırken hata: " . $db->error);
                }
                $stmt2->bind_param("ii", $gorev_id, $calisan_id);
                $stmt2->execute();

                if ($stmt2->errno) {
                    die("Çalışanları eklerken hata: " . $stmt2->error);
                }
                $stmt2->close();  
            }

            echo "Görev ve çalışanlar başarıyla eklendi.";
        } else {
            
        }
        $stmt->close();  
    } else {
        
    }

    if ($gorev_id_to_delete > 0) {
        // First, delete the references in gorevler_calisanlar table
        $sql = "DELETE FROM gorevler_calisanlar WHERE gorev_id=?";
        $stmt = $db->prepare($sql);
        if ($stmt === false) {
            die("SQL sorgusu hazırlanırken hata: " . $db->error);
        }
        $stmt->bind_param("i", $gorev_id_to_delete);
        if (!$stmt->execute()) {
            die("Görev çalışan referanslarını silerken hata: " . $stmt->error);
        }
        $stmt->close();

        // Then, delete the task itself
        $sql = "DELETE FROM gorevler WHERE gorev_id=?";
        $stmt = $db->prepare($sql);
        if ($stmt === false) {
            die("SQL sorgusu hazırlanırken hata: " . $db->error);
        }
        $stmt->bind_param("i", $gorev_id_to_delete);
        if (!$stmt->execute()) {
            die("Görev silerken hata: " . $stmt->error);
        }
        if ($stmt->affected_rows > 0) {
            echo "Görev başarıyla silindi.";
        } else {
            echo "Görev silme sırasında bir hata oluştu.";
        }
        $stmt->close();
    }
}


$projeler = "";
$sql = "SELECT * FROM projeler";
$result = $db->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $projeler .= "<a href='?proje_id=".$row["proje_id"]."'>Proje Adı: " . $row["proje_adi"]. " - Başlangıç Tarihi: " . $row["baslangic_tarihi"]. " - Bitiş Tarihi: " . $row["bitis_tarihi"]. "</a><br>";
    }
} else {
    $projeler = "Hiç proje bulunamadı.";
}

if (isset($_GET["proje_id"])) {
    $proje_id = $_GET["proje_id"];
    $sql = "SELECT * FROM projeler WHERE proje_id = $proje_id";
    $result = $db->query($sql);

    $proje_detay = "";
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $proje_detay .= "Proje Adı: " . $row["proje_adi"]. " - Başlangıç Tarihi: " . $row["baslangic_tarihi"]. " - Bitiş Tarihi: " . $row["bitis_tarihi"]. "<br>";
        }
    } else {
        $proje_detay = "Proje bulunamadı.";
    }

    $gorevler = "";
    $sql = "SELECT * FROM gorevler WHERE proje_id = $proje_id";
    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $gorevler .= "Görev Adı: " . $row["gorev_adi"]. " - Başlangıç Tarihi: " . $row["baslangic_tarihi"]. " - Bitiş Tarihi: " . $row["bitis_tarihi"]. "<br>";
        }
    } else {
        $gorevler = "Bu projeye ait hiç görev bulunamadı.";
    }

    $calisanlar = "";
    $sql = "SELECT calisanlar.ad_soyad FROM gorevler_calisanlar JOIN calisanlar ON gorevler_calisanlar.calisan_id = calisanlar.calisan_id WHERE gorevler_calisanlar.gorev_id IN (SELECT gorev_id FROM gorevler WHERE proje_id = ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $proje_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $calisanlar .= $row["ad_soyad"]."<br>";
        }
    } else {
        $calisanlar = "Bu projede çalışan bulunamadı.";
    }
}


// Veritabanı bağlantısını kapat
$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>BCO PROJE YÖNETİM SİSTEMİ</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>BCO PROJE YÖNETİM SİSTEMİ</h1>
            <button class="button" id="showProjects">PROJELER</button>
            <button class="button" id="showCalisanlar">ÇALIŞANLAR</button>
        </div>
    </header>
    <div class="container" id="content">
        <!-- Ana içerik burada olacak -->
    </div>

    <script>
        $(document).ready(function () {
            $("#showProjects").click(function () {
                $("#content").html(`
                <button onclick="listProjects()">Proje Listele</button>
                <button onclick="showForm()">Proje Ekle</button>
                <button onclick="showDeleteForm()">Proje Sil</button>
            `);
            });

            $("#showCalisanlar").click(function () {
                $("#content").html(`
                <button onclick="listCalisanlar()">Çalışan Listele</button>
                <button onclick="showCalisanForm()">Çalışan Ekle</button>
                <button onclick="showCalisanDeleteForm()">Çalışan Sil</button>
            `);
            });
        });

        function listProjects() {
            $("#content").html(`<?php echo $projeler; ?>`);
        }

        function showForm() {
            $("#content").html(`
            <form action="" method="post">
                <label for="projeAdi">Proje Adı:</label>
                <input type="text" name="projeAdi" required>
                <label for="baslangicTarihi">Başlangıç Tarihi:</label>
                <input type="date" name="baslangicTarihi" required>
                <label for="bitisTarihi">Bitiş Tarihi:</label>
                <input type="date" name="bitisTarihi" required>
                <button type="submit" name="projeEkle">Proje Ekle</button>
            </form>
        `);
        }

        function showDeleteForm() {
            $("#content").html(`
            <form action="" method="post">
                <label for="projeAdi">Silinecek Proje Adı:</label>
                <input type="text" name="projeAdi" required>
                <button type="submit" name="projeSil">Proje Sil</button>
            </form>
        `);
        }

        function listCalisanlar() {
            $("#content").html(`<?php echo $calisanlar; ?>`);
        }

        function showCalisanForm() {
            $("#content").html(`
            <form action="" method="post">
                <label for="adSoyad">Ad Soyad:</label>
                <input type="text" name="adSoyad" required>
                <button type="submit" name="calisanEkle">Çalışan Ekle</button>
            </form>
        `);
        }

        function showCalisanDeleteForm() {
            $("#content").html(`
            <form action="" method="post">
                <label for="calisanId">Silinecek Çalışan ID:</label>
                <input type="number" name="calisanId" required>
                <button type="submit" name="calisanSil">Çalışan Sil</button>
            </form>
        `);
        }
    </script>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>Proje Yönetimi</title>
</head>
<body>
    <?php
    $db = new mysqli('localhost', 'root', '', 'proje');
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    if (isset($_GET["proje_id"])): 
        $proje_id = $_GET["proje_id"];
        
        // Projede çalışanları veritabanından alın
        $sql = "SELECT calisanlar.ad_soyad FROM gorevler_calisanlar JOIN calisanlar ON gorevler_calisanlar.calisan_id = calisanlar.calisan_id WHERE gorevler_calisanlar.gorev_id IN (SELECT gorev_id FROM gorevler WHERE proje_id = ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $proje_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $calisanlar = "";
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $calisanlar .= $row["ad_soyad"]."<br>";
            }
        } else {
            $calisanlar = "Bu projede çalışan bulunamadı.";
        }
    ?>
        <h2>Proje Detayları</h2>
        <?php echo $proje_detay; ?>

        <h2>Görevler</h2>
        <?php 
        // Görevleri listele
        $sql = "SELECT * FROM gorevler WHERE proje_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $proje_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo $row["gorev_adi"];
                echo '<form action="" method="post">
                    <input type="hidden" name="gorev_id_to_delete" value="' . $row["gorev_id"] . '">
                    <input type="submit" value="Sil">
                </form><br>';
            }
        } else {
            echo "Bu projede görev bulunamadı.";
        }
        ?>

        <h2>Çalışanlar</h2>
        <?php echo $calisanlar; ?>

        <h2>Görev Ekle</h2>
        <form action="" method="post">
            <label for="gorev_adi">Görev Adı:</label><br>
            <input type="text" id="gorev_adi" name="gorev_adi"><br>
            <label for="baslangic_tarihi">Başlangıç Tarihi:</label><br>
            <input type="date" id="baslangic_tarihi" name="baslangic_tarihi"><br>
            <label for="bitis_tarihi">Bitiş Tarihi:</label><br>
            <input type="date" id="bitis_tarihi" name="bitis_tarihi"><br>
            <label for="calisanlar">Çalışanlar:</label><br>
            <?php
            // Tüm çalışanları veritabanından alın
            $sql = "SELECT * FROM calisanlar";
            $result = $db->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<input type="checkbox" id="calisan' . $row["calisan_id"] . '" name="calisanlar[]" value="' . $row["calisan_id"] . '">';
                    echo '<label for="calisan' . $row["calisan_id"] . '">' . $row["ad_soyad"] . '</label><br>';
                }
            }
            ?>
            <input type="hidden" name="proje_id" value="<?php echo $_GET['proje_id']; ?>">
            <input type="submit" value="Görev Ekle">
        </form>
    <?php endif; ?>
</body>
</html>
