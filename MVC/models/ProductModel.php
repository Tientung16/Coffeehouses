<?php
class ProductModel extends DB
{

    public function ListAll()
    {
        $sql = "SELECT * FROM product";
        return mysqli_query($this->con, $sql);
    }
    public function countProduct()
    {
        $sql = "SELECT count(*) FROM product";
        return mysqli_query($this->con, $sql);
    }
    public function showNum()
    {
        $sql = "SELECT count(*) FROM product";
        return mysqli_query($this->con, $sql);
    }
    public function showNumId($id)
    {
        $sql = "SELECT count(*) FROM product WHERE category_id=$id";
        return mysqli_query($this->con, $sql);
    }

    public function ListItem($id)
    {
        $sql = "SELECT * FROM product where product_id =$id";
        return mysqli_query($this->con, $sql);
    }

    public function ListItemId($id)
    {
        $sql = "SELECT * FROM product where category_id =$id";
        return mysqli_query($this->con, $sql);
    }


    public function ListItemProduct($id)
    {
        $sql = "SELECT * FROM variant, product, category 
        WHERE product.product_id=$id 
        AND variant.product_id=$id 
        AND product.category_id=category.category_id";
        return mysqli_query($this->con, $sql);
    }
    public function showPrice($id)
    {
        $sql = "SELECT * FROM variant WHERE product_id=$id";
        return mysqli_query($this->con, $sql);
    }

    public function showComment($id)
    {
        $sql = "SELECT * from comment, user 
        where comment.user_id = user.user_id 
        AND product_id=$id ORDER BY comment_date DESC";
        return mysqli_query($this->con, $sql);
    }

    public function deleteComment($id)
    {
        $sql = "DELETE FROM comment WHERE comment_id='$id'";
        return mysqli_query($this->con, $sql);
    }


    public function ProductRelated($id)
    {
        $qr = "SELECT category_id FROM product WHERE product_id=$id";
        $checkid = mysqli_query($this->con, $qr);
        $row = mysqli_fetch_assoc($checkid);
        $category_id = $row['category_id'];
        $sql = "SELECT * FROM product 
        INNER JOIN variant ON product.product_id = variant.product_id 
        WHERE size = 'Nh???' AND product.category_id=$category_id
        ORDER BY import_date DESC";
        return mysqli_query($this->con, $sql);
    }

    public function phantrang()
    {
        $record_per_page = 6;
        $page = '';
        $output = '';
        if (isset($_POST["page"])) {
            $page = $_POST["page"];
        } else {
            $page = 1;
        }
        $start_from = ($page - 1) * $record_per_page;
        $sql = "SELECT * FROM product 
        INNER JOIN variant ON product.product_id = variant.product_id 
        WHERE size = 'Nh???'";

        if (isset($_POST['category'])) {
            $category_id = implode("','", $_POST['category']);
            $sql .= "AND category_id IN ('$category_id')";
        }
        if (isset($_POST['locSP'])) {
            $locSP = $_POST['locSP'];
            $sql .= " ORDER BY import_date $locSP LIMIT $start_from, $record_per_page";
        }

        $phantrang = mysqli_query($this->con, $sql);
?>
        <div class="list-product grid-3" id="pagination_data">
            <?php
            while ($row = mysqli_fetch_array($phantrang)) {
            ?>
                <a href="<?= BASE_URL ?>/home/product/<?= $row['product_id'] ?>" class="product-cart">
                    <div class="product-cart__tags justify-content-right">
                        <!-- <div class="tag-new">new</div> -->
                        <?php
                        if ($row['price_sale'] > 0) {
                        ?>
                            <div class="tag-discount"><?= $row['price_sale'] ?>%</div>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="product-cart__img">
                        <img src="<?= BASE_URL ?>/<?= $row['thumbnail'] ?>" alt="">
                    </div>
                    <div class="product-cart__info">
                        <div class="info-title"><?= $row['product_name'] ?></div>
                        <div class="info-rating">
                            <div class="rating-list">
                                <i class="rating-icon fas fa-star"></i>
                                <i class="rating-icon fas fa-star"></i>
                                <i class="rating-icon fas fa-star"></i>
                                <i class="rating-icon fas fa-star"></i>
                                <i class="rating-icon fas fa-star"></i>
                            </div>
                            <p class="rating-text">(2 ????nh gi??)</p>
                        </div>
                        <div class="info-price">
                            <?php
                            if ($row['price_sale'] > 0) {
                                $price = $row['price']; // 22
                                $sale = $row['price_sale']; // 50
                                $price_sale = ($sale / 100) * $price;
                                $priceTop = $price - $price_sale;
                            ?>
                                <div class="info-origin-price"><?= number_format($priceTop, 0, ",", ".") ?> VN??</div>
                                <div class="info-sale-price"><?= number_format($row['price'], 0, ",", ".") ?> VN??</div>
                            <?php
                            } else {
                            ?>
                                <div class="info-origin-price"><?= number_format($row['price'], 0, ",", ".") ?> VN??</div>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="btn btn--primary btn-order-product">?????t h??ng</div>
                    </div>
                </a>
            <?php
            }
            ?>
        </div>

        <?php
        $page_query = "SELECT * FROM product 
        INNER JOIN variant ON product.product_id = variant.product_id 
        WHERE size = 'Nh???'";
        if (isset($_POST['category'])) {
            $category_id = implode("','", $_POST['category']);
            $page_query .= "AND category_id IN ('$category_id')";
        }

        $page_query .= " ORDER BY import_date DESC";


        $page_result = mysqli_query($this->con, $page_query);
        $total_records = mysqli_num_rows($page_result);
        $total_pages = ceil($total_records / $record_per_page);
        ?>
        <div class="pagination">
            <?php
            for ($i = 1; $i <= $total_pages; $i++) {
            ?>
                <div class="pagination_link" id="<?= $i ?>"><?= $i ?></div>
            <?php
            }
            ?>
            <div>
            <?php
        }

        public function showNumAjax()
        {
            $sql = "SELECT * FROM product 
        INNER JOIN variant ON product.product_id = variant.product_id 
        WHERE size = 'Nh???'";
            if (isset($_POST['category'])) {
                $category_id = implode("','", $_POST['category']);
                $sql .= "AND category_id IN ('$category_id')";
            }
            $result = mysqli_query($this->con, $sql);
            $num = mysqli_num_rows($result);
            ?>
                <p><span><?= $num ?></span> S???n ph???m</p>
        <?php
        }

        public function ShowProductList()
        {
            $sql = "SELECT * FROM product";
            return mysqli_query($this->con, $sql);
        }
        public function ListAllAdmin()
        {
            $sql = "SELECT * FROM product 
        INNER JOIN variant ON product.product_id = variant.product_id 
        WHERE size = 'Nh???'
        ORDER BY import_date DESC";
            return mysqli_query($this->con, $sql);
        }
        public function ListAllAdmin1()
        {
            $sql = "SELECT * FROM product 
        INNER JOIN variant ON product.product_id = variant.product_id 
        WHERE size = 'Nh???'
        ORDER BY import_date DESC limit 12";
            return mysqli_query($this->con, $sql);
        }
        public function ListAllCt($id)
        {
            $sql = "SELECT * FROM product 
        INNER JOIN variant ON product.product_id = variant.product_id 
        WHERE size = 'Nh???' AND category_id=$id
        ORDER BY import_date DESC";
            return mysqli_query($this->con, $sql);
        }

        public function ListSearch($id)
        {
            $sql = "SELECT * FROM product 
        INNER JOIN variant ON product.product_id = variant.product_id 
        INNER JOIN category ON product.category_id = category.category_id 
        WHERE size = 'Nh???' AND product_name like '%$id%'
        OR size = 'Nh???' AND category_name like '%$id%'
        ORDER BY import_date DESC";
            return mysqli_query($this->con, $sql);
        }
        public function ListNumSearch($id)
        {
            $sql = "SELECT count(*) FROM product 
        INNER JOIN variant ON product.product_id = variant.product_id 
        INNER JOIN category ON product.category_id = category.category_id 
        WHERE size = 'Nh???' AND product_name like '%$id%'
        OR size = 'Nh???' AND category_name like '%$id%'";
            return mysqli_query($this->con, $sql);
        }

        public function showProductSelling()
        {
            $sql = "SELECT product.product_id, product.product_name, product.price_sale, 
        product.thumbnail, variant.size, variant.price,
        SUM(order_details.num) as num
        FROM variant, product, order_details
        WHERE product.product_id=variant.product_id
        and variant.variant_id=order_details.variant_id 
        GROUP BY product.product_id
        ORDER by order_details.num DESC";
            return mysqli_query($this->con, $sql);
        }

        public function showProductNew()
        {
            $sql = "SELECT * FROM product 
        INNER JOIN variant ON product.product_id = variant.product_id 
        INNER JOIN comment ON product.product_id = comment.product_id 
        WHERE size = 'V???a' 
        ORDER BY import_date DESC";
            return mysqli_query($this->con, $sql);
        }

        public function ListItemVariant($id)
        {
            $sql = "SELECT * FROM product INNER JOIN variant ON product.product_id = variant.product_id WHERE size = 'V???a'";
            return mysqli_query($this->con, $sql);
        }

        public function showProducsAll($id)
        {
            $sql = "SELECT * FROM product INNER JOIN variant ON product.product_id = variant.product_id WHERE product_id = $id";
            return mysqli_query($this->con, $sql);
        }
        public function ShowVariant($id)
        {
            $sql = "SELECT * FROM variant where product_id = $id";
            return mysqli_query($this->con, $sql);
        }

        public function add()
        {
            $error = false;
            if (isset($_POST['name']) && !empty($_POST['name'])) {
                $category_id = $_POST['category_id'];
                $name = $_POST['name']; //T??n Products

                $sizeM = $_POST['sizeM']; //SIZE M
                $sizeML = $_POST['sizeML']; //SIZE ML
                $sizeL = $_POST['sizeL']; //SIZE L

                $priceSale = $_POST['priceSale'];
                $numProduct = $_POST['numProduct'];
                $description = $_POST['description'];
                $import_date = date('Y-m-d H:i:s');

                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    // D??? li???u g???i l??n server kh??ng b???ng ph????ng th???c post
                    echo '
                    <script>
                        alert("D??? li???u ch??a c??")
                        window.location.assign("./Category");
                    </script>
                ';
                    die;
                }
                // Ki???m tra c?? d??? li???u thumbnail trong $_FILES kh??ng
                // N???u kh??ng c?? th?? d???ng
                if (!isset($_FILES["thumbnail"])) {
                    echo '
                    <script>
                        alert("D??? li???u kh??ng ????ng c???u tr??c")
                        window.location.assign("./product");
                    </script>
                ';
                    die;
                }
                // Ki???m tra d??? li???u c?? b??? l???i kh??ng
                if ($_FILES["thumbnail"]['error'] != 0) {
                    echo '
                    <script>
                        alert("D??? li???u upload b??? l???i")
                        window.location.assign("./product");
                    </script>
                ';
                    die;
                }
                // ???? c?? d??? li???u upload, th???c hi???n x??? l?? file upload
                //Th?? m???c b???n s??? l??u file upload
                $target_dir    = "mvc/public/images/products/";
                //V??? tr?? file l??u t???m trong server (file s??? l??u trong uploads, v???i t??n gi???ng t??n ban ?????u)
                $target_file   = $target_dir . basename($_FILES["thumbnail"]["name"]);
                $allowUpload   = true;
                //L???y ph???n m??? r???ng c???a file (jpg, png, ...)
                $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                // C??? l???n nh???t ???????c upload (bytes)
                $maxfilesize   = 800000;
                ////Nh???ng lo???i file ???????c ph??p upload
                $allowtypes    = array('jpg', 'png', 'jpeg', 'gif');
                if (isset($_POST["submit"])) {
                    //Ki???m tra xem c?? ph???i l?? ???nh b???ng h??m getimagesize
                    $check = getimagesize($_FILES["thumbnail"]["tmp_name"]);
                    if ($check !== false) {
                        // echo "????y l?? file ???nh - " . $check["mime"] . ".";
                        $allowUpload = true;
                    } else {
                        echo '
                    <script>
                        alert("Kh??ng ph???i file ???nh")
                        window.location.assign("./product");
                    </script>
                ';
                        $allowUpload = false;
                    }
                }
                // Ki???m tra k??ch th?????c file upload cho v?????t qu?? gi???i h???n cho ph??p
                if ($_FILES["thumbnail"]["size"] > $maxfilesize) {
                    echo '
                    <script>
                        alert("Kh??ng ???????c upload ???nh l???n h??n 800000 (bytes).")
                        window.location.assign("./product");
                    </script>
                ';
                    $allowUpload = false;
                }

                // Ki???m tra ki???u file
                if (!in_array($imageFileType, $allowtypes)) {
                    echo '
                    <script>
                        alert("Ch??? ???????c upload c??c ?????nh d???ng JPG, PNG, JPEG, GIF")
                        window.location.assign("./product");
                    </script>
                ';
                    $allowUpload = false;
                }

                if ($allowUpload) {
                    // X??? l?? di chuy???n file t???m ra th?? m???c c???n l??u tr???, d??ng h??m move_uploaded_file
                    if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file)) {
                        $result = mysqli_query($this->con, "INSERT INTO product(category_id, product_name,thumbnail, description, price_sale , amount , active , import_date) 
            VALUES ('" . $category_id . "','" . $name . "','" . $target_file . "','" . $description . "','" . $priceSale . "','" . $numProduct . "','" . 1 . "','" . $import_date . "')");
                $checkProducID = mysqli_query($this->con, "SELECT * FROM product WHERE import_date ='$import_date'");
                $id = mysqli_fetch_assoc($checkProducID);
                $product_id = $id['product_id'];
                if ($sizeM !== "") {
                    mysqli_query($this->con, "INSERT INTO variant(product_id, size, price) 
                VALUES('" . $product_id . "','Nh???','" . $sizeM . "')");
                } else {
                    mysqli_query($this->con, "INSERT INTO variant(product_id, size, price) 
                VALUES('" . $product_id . "','Nh???','" . 0 . "')");
                }
                if ($sizeML !== "") {
                    mysqli_query($this->con, "INSERT INTO variant(product_id, size, price) 
                VALUES('" . $product_id . "','V???a','" . $sizeML . "')");
                } else {
                    mysqli_query($this->con, "INSERT INTO variant(product_id, size, price) 
                VALUES('" . $product_id . "','V???a','" . 0 . "')");
                }
                if ($sizeL !== "") {
                    mysqli_query($this->con, "INSERT INTO variant(product_id, size, price) 
                VALUES('" . $product_id . "','L???n','" . $sizeL . "')");
                } else {
                    mysqli_query($this->con, "INSERT INTO variant(product_id, size, price) 
                VALUES('" . $product_id . "','L???n','" . 0 . "')");
                }
                mysqli_close($this->con);
                    } else {
                        echo '
                    <script>
                        alert("C?? l???i x???y ra khi upload file")
                        // window.location.assign("./product");
                    </script>
                ';
                    }
                } else {
                    echo '
                    <script>
                        alert("Kh??ng upload ???????c file, c?? th??? do file l???n, ki???u file kh??ng ????ng ...")
                        window.location.assign("./product");
                    </script>
                ';
                }

            //     $result = mysqli_query($this->con, "INSERT INTO product(category_id, product_name,thumbnail, description, price_sale , amount , active , import_date) 
            // VALUES ('" . $category_id . "','" . $name . "','" . $target_file . "','" . $description . "','" . $priceSale . "','" . $numProduct . "','" . 1 . "','" . $import_date . "')");
            //     $checkProducID = mysqli_query($this->con, "SELECT * FROM product WHERE import_date ='$import_date'");
            //     $id = mysqli_fetch_assoc($checkProducID);
            //     $product_id = $id['product_id'];
            //     if ($sizeM !== "") {
            //         mysqli_query($this->con, "INSERT INTO variant(product_id, size, price) 
            //     VALUES('" . $product_id . "','Nh???','" . $sizeM . "')");
            //     } else {
            //         mysqli_query($this->con, "INSERT INTO variant(product_id, size, price) 
            //     VALUES('" . $product_id . "','Nh???','" . 0 . "')");
            //     }
            //     if ($sizeML !== "") {
            //         mysqli_query($this->con, "INSERT INTO variant(product_id, size, price) 
            //     VALUES('" . $product_id . "','V???a','" . $sizeML . "')");
            //     } else {
            //         mysqli_query($this->con, "INSERT INTO variant(product_id, size, price) 
            //     VALUES('" . $product_id . "','V???a','" . 0 . "')");
            //     }
            //     if ($sizeL !== "") {
            //         mysqli_query($this->con, "INSERT INTO variant(product_id, size, price) 
            //     VALUES('" . $product_id . "','L???n','" . $sizeL . "')");
            //     } else {
            //         mysqli_query($this->con, "INSERT INTO variant(product_id, size, price) 
            //     VALUES('" . $product_id . "','L???n','" . 0 . "')");
            //     }
            //     mysqli_close($this->con);
                if ($error !== false) {
                    echo '
                    <script>
                        alert("C?? l???i khi th??m s???n ph???m, xin m???i th??? l???i")
                        window.location.assign("./product");
                    </script>
                ';
                    exit;
                } else {
                    echo '
                <script>
                    alert("Th??m s???n ph???m th??nh c??ng")
                    window.location.assign("./product");
                </script>
            ';
                    exit;
                }
            } else {
                echo '
            <script>
                alert("B???n ch??a nh???p th??ng tin")
                window.location.assign("./product");
            </script>
        ';
                exit;
            }
        }

        // EDIT PRODUCT
        public function Edit()
        {
            $error = false;
            if (isset($_POST['name']) && !empty($_POST['name'])) {
                $category_id = $_POST['category_id'];
                $name = $_POST['name']; //T??n Products
                $product_id = $_POST['product_id']; //ID Products

                $sizeM = $_POST['Nh???']; //SIZE M
                $sizeML = $_POST['V???a']; //SIZE ML
                $sizeL = $_POST['L???n']; //SIZE L

                $priceSale = $_POST['priceSale'];
                $numProduct = $_POST['numProduct'];
                $description = $_POST['description'];

                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    // D??? li???u g???i l??n server kh??ng b???ng ph????ng th???c post
                    echo '
                    <script>
                        alert("D??? li???u ch??a c??")
                        window.location.assign("./Product");
                    </script>
                ';
                    die;
                }
                // Ki???m tra c?? d??? li???u thumbnail trong $_FILES kh??ng
                // N???u kh??ng c?? th?? d???ng
                if (!isset($_FILES["thumbnail"])) {
                    echo '
                    <script>
                        alert("D??? li???u kh??ng ????ng c???u tr??c")
                        window.location.assign("./Product");
                    </script>
                ';
                    die;
                }
                // Ki???m tra d??? li???u c?? b??? l???i kh??ng
                if ($_FILES["thumbnail"]['error'] != 0) {
                    $sql = "SELECT * FROM product where product_id='$product_id'";
                    $row = mysqli_query($this->con, $sql);
                    $result = mysqli_fetch_assoc($row);
                    $thumbnail = $result['thumbnail'];
                    $kq = mysqli_query($this->con, "UPDATE product SET  category_id = '$category_id',product_name='$name', price_sale = '$priceSale', amount = '$numProduct' , description = '$description'  WHERE product_id = '$product_id' ");

                    if (isset($sizeM)) {
                        mysqli_query($this->con, "UPDATE variant SET  price = '$sizeM' WHERE product_id = '$product_id' AND size = 'Nh???'");
                    }
                    if (isset($sizeML)) {
                        mysqli_query($this->con, "UPDATE variant SET  price = '$sizeML' WHERE product_id = '$product_id' AND size = 'V???a'");
                    }
                    if (isset($sizeL)) {
                        mysqli_query($this->con, "UPDATE variant SET  price = '$sizeL' WHERE product_id = '$product_id' AND size = 'L???n'");
                    }

                    echo '
                    <script>
                        alert("S???a s???n ph???m th??nh c??ng")
                        window.location.assign("./Product");
                    </script>
                ';
                    die;
                }
                // ???? c?? d??? li???u upload, th???c hi???n x??? l?? file upload
                //Th?? m???c b???n s??? l??u file upload
                $target_dir    = "mvc/public/images/products/";
                //V??? tr?? file l??u t???m trong server (file s??? l??u trong uploads, v???i t??n gi???ng t??n ban ?????u)
                $target_file   = $target_dir . basename($_FILES["thumbnail"]["name"]);
                $allowUpload   = true;
                //L???y ph???n m??? r???ng c???a file (jpg, png, ...)
                $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                // C??? l???n nh???t ???????c upload (bytes)
                $maxfilesize   = 800000;
                ////Nh???ng lo???i file ???????c ph??p upload
                $allowtypes    = array('jpg', 'png', 'jpeg', 'gif');
                if (isset($_POST["submit"])) {
                    //Ki???m tra xem c?? ph???i l?? ???nh b???ng h??m getimagesize
                    $check = getimagesize($_FILES["thumbnail"]["tmp_name"]);
                    if ($check !== false) {
                        // echo "????y l?? file ???nh - " . $check["mime"] . ".";
                        $allowUpload = true;
                    } else {
                        echo '
                    <script>
                        alert("Kh??ng ph???i file ???nh")
                        window.location.assign("./Product");
                    </script>
                ';
                        $allowUpload = false;
                        exit;
                    }
                }
                // Ki???m tra k??ch th?????c file upload cho v?????t qu?? gi???i h???n cho ph??p
                if ($_FILES["thumbnail"]["size"] > $maxfilesize) {
                    echo '
                    <script>
                        alert("Kh??ng ???????c upload ???nh l???n h??n 800000 (bytes).")
                        window.location.assign("./Product");
                    </script>
                ';
                    $allowUpload = false;
                    exit;
                }

                // Ki???m tra ki???u file
                if (!in_array($imageFileType, $allowtypes)) {
                    echo '
                    <script>
                        alert("Ch??? ???????c upload c??c ?????nh d???ng JPG, PNG, JPEG, GIF")
                        window.location.assign("./Product");
                    </script>
                ';
                    $allowUpload = false;
                    exit;
                }

                if ($allowUpload) {
                    // X??? l?? di chuy???n file t???m ra th?? m???c c???n l??u tr???, d??ng h??m move_uploaded_file
                    if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file)) {
                    } else {
                        echo '
                    <script>
                        alert("C?? l???i x???y ra khi upload file")
                        window.location.assign("./Product");
                    </script>
                ';
                        exit;
                    }
                } else {
                    echo '
                    <script>
                        alert("Kh??ng upload ???????c file, c?? th??? do file l???n, ki???u file kh??ng ????ng ...")
                        window.location.assign("./Product");
                    </script>
                ';
                    exit;
                }
                $result = mysqli_query($this->con, "UPDATE product SET  thumbnail = '$target_file', category_id = '$category_id',product_name='$name',thumbnail = '$target_file', description = '$description', price_sale = '$priceSale', amount = '$numProduct'
            WHERE product_id = '$product_id'");

                if (isset($sizeM)) {
                    mysqli_query($this->con, "UPDATE variant SET  price = '$sizeM' WHERE product_id = '$product_id' AND size = 'Nh???'");
                }
                if (isset($sizeML)) {
                    mysqli_query($this->con, "UPDATE variant SET  price = '$sizeML' WHERE product_id = '$product_id' AND size = 'V???a'");
                }
                if (isset($sizeL)) {
                    mysqli_query($this->con, "UPDATE variant SET  price = '$sizeL' WHERE product_id = '$product_id' AND size = 'L???n'");
                }

                mysqli_close($this->con);
                if ($error !== false) {
                    echo '
                    <script>
                        alert("???? x???y ra l???i, vui l??ng th??? l???i");
                        window.location.assign("./Product");
                        
                    </script>
                ';
                    exit;
                } else {
                    echo '
                    <script>
                        alert("S???a s???n ph???m th??nh c??ng");
                        window.location.assign("./Product");
                    </script>
                ';
                    exit;
                }
            } else {
                echo '
                    <script>
                        alert("B???n ch??a nh???p th??ng tin");
                        window.location.assign("./Product");
                    </script>
                ';
                exit;
            }
        }
        public function deleteVariant($id)
        {
            $sql = "DELETE FROM variant where product_id=$id";
            return mysqli_query($this->con, $sql);
        }

        public function deleteProduct($id)
        {
            $sql = "DELETE FROM product where product_id=$id";
            return mysqli_query($this->con, $sql);
        }

        public function showCart()
        {
            for ($i = 0; $i < sizeof($_SESSION['giohang']); $i++) {
                $num = $_SESSION['giohang'][$i][2];
                $size = $_SESSION['giohang'][$i][0];
                $id = $_SESSION['giohang'][$i][1];
                $sql = "SELECT * FROM product ,variant 
                WHERE variant.product_id=$id 
            AND size='$size' 
            AND variant.product_id=product.product_id";
            }
            return mysqli_query($this->con, $sql);
        }

        public function checkoutAct()
        {
            $error = false;
            if (isset($_POST['address']) && !empty($_POST['address']) && isset($_POST['phone']) && !empty($_POST['phone'])) {
                $name = $_POST['name'];
                $phone = $_POST['phone'];
                $address = $_POST['address'];
                $note = $_POST['note'];
                $user_id = $_POST['user_id'];
                $banking = $_POST['banking'];
                $order_date = date('Y-m-d H:i:s');
                $status = "??ang ti???n h??nh";

                $cart = [];
                if (isset($_SESSION['giohang'])) {
                    $cart = $_SESSION['giohang'];
                    // $cart = json_decode($json, true);
                }
                $result = mysqli_query($this->con, "INSERT INTO orders (user_id, address, phone, note, method,status, order_date) 
            VALUES ('" . $user_id . "','" . $address . "','" . $phone . "','" . $note . "','" . $banking . "','" . $status . "','" . $order_date . "')");
                if (!$result) {
                    if (strpos(mysqli_error($this->con), "Duplicate entry") !== FALSE) {
                        echo json_encode(array(
                            'status' => 0,
                            'message' => 'C?? l???i khi ?????t h??ng, vui l??ng th??? l???i'
                        ));
                        exit;
                    }
                }

                // mysqli_close($this->con);
                if ($error !== false) {
                    echo json_encode(array(
                        'status' => 0,
                        'message' => 'C?? l???i khi ?????t ?????t h??ng, xin m???i th??? l???i'
                    ));
                    exit;
                } else {
                    // l???y ra id orders
                    $sql = "SELECT * FROM orders WHERE order_date = '$order_date'";

                    $order = mysqli_query($this->con, $sql);
                    foreach ($order as $item) {
                        $orderId = $item['order_id'];
                    }
                    // l???y ra id user
                    $id_user = $_SESSION['userlogin'][3];

                    // l???y ra id variant 
                    if (isset($_SESSION['giohang'])) {
                        $idProduct = [];
                        for ($i = 0; $i < sizeof($_SESSION['giohang']); $i++) {
                            $idProduct[] = $_SESSION['giohang'][$i][1];
                            $sizeProduct[] = "'" . $_SESSION['giohang'][$i][0] . "'";
                        }
                        if (count($idProduct) > 0) {
                            $idProduct = implode(',', $idProduct); // c???t m???ng
                            $sizeProduct = implode(',', $sizeProduct); // c???t m???ng
                            $sql = "SELECT * FROM variant,product where variant.product_id in ($idProduct) AND size in ($sizeProduct) AND variant.product_id=product.product_id";
                            $cartList = mysqli_query($this->con, $sql);
                        } else {
                            $cartList = [];
                        }
                        foreach ($cartList as $item) {
                            $price_total = 0;
                            $num = 0;
                            for ($i = 0; $i < sizeof($_SESSION['giohang']); $i++) {
                                if ($_SESSION['giohang'][$i][1] == $item['product_id']) {
                                    $num = $_SESSION['giohang'][$i][2];
                                    $item[''] = +($item['price']-($item['price']*$item['price_sale']/100));
                                    // break;
                                }
                            }
                            $price_total = $item[''] * $num;
                            $sql = "INSERT into order_details (order_id, variant_id, price_total, num) 
                        values ('$orderId', '" . $item['variant_id'] . "','$price_total','$num')";
                            mysqli_query($this->con, $sql);
                            $sql_soluong = "UPDATE product set amount=amount-$num where product_id in('$idProduct')";
                            mysqli_query($this->con,$sql_soluong);
                        }
                    }
                    unset($_SESSION['giohang']);
                    echo '
                    <script>
                        alert("?????t h??ng th??nh c??ng");
                        window.location.assign("./history");
                    </script>
                ';
                    exit;
                }
            } else {

                echo json_encode(array(
                    'status' => 0,
                    'message' => 'B???n ch??a nh???p th??ng tin'
                ));
                exit;
            }
        }

        // public function showHistoty($id)
        // {
        //     $sql = "SELECT * FROM orders, user WHERE orders.user_id=1 AND orders.user_id=user.user_id ORDER BY order_date DESC";
        //     return mysqli_query($this->con, $sql);
        // }
        public function showHistoty($id)
        {
            $sql = "SELECT * FROM orders, user WHERE orders.user_id=$id AND orders.user_id=user.user_id ORDER BY order_date DESC";
            return mysqli_query($this->con, $sql);
        }

        public function showHistoryDetails($id)
        {
            $sql = "SELECT * FROM order_details, product,variant 
        WHERE order_id=$id
        AND order_details.variant_id=variant.variant_id 
        and variant.product_id = product.product_id";
            return mysqli_query($this->con, $sql);
        }
        // public function showHistoryDetails($user_id)
        // {
        //     $sql = "SELECT * FROM order_details, product,variant 
        // WHERE user_id=$user_id
        // AND order_details.variant_id=variant.variant_id 
        // and variant.product_id = product.product_id";
        //     return mysqli_query($this->con, $sql);
        // }
        public function showStatus($id)
        {
            $sql = "SELECT * FROM orders WHERE order_id=$id";
            return mysqli_query($this->con, $sql);
        }

        public function updateOrder($status, $id)
        {
            $sql = "UPDATE orders SET status='$status' WHERE order_id =$id";
            $result = mysqli_query($this->con, $sql);
            if ($result !== false) {
                echo '
                <script>
                    alert("C???p nh???t th??nh c??ng");
                    history.back();
                </script>
            ';
                exit;
            } else {
                echo '
                <script>
                    alert("???? x???y ra l???i");
                    history.back();
                </script>
            ';
                exit;
            }
        }
    }
