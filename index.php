<!DOCTYPE html>
<html lang="en">

<?php include("inc/head.php") ?>

<body class="bg-white text-base dark:bg-gray-800 dark:text-gray-100">
    <div id="app" data-v-app="">

        <?php
        if (isset($_SESSION["success_message"])) {
            echo '<script type="text/javascript">toastr.success("' . $_SESSION["success_message"] . '")</script>';
            unset($_SESSION["success_message"]);
        }
        if (isset($_SESSION["error_message"])) {
            echo '<script type="text/javascript">toastr.error("' . $_SESSION["error_message"] . '")</script>';
            unset($_SESSION["error_message"]);
        }

        ?>

        <?php
        include("inc/header.php"); ?>

        <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet" />

        <div id="wrapper">

            <div class="mt-8 w-9/12 mx-auto">

                <section class="mt-4">
                    <nav class="flex px-5 text-gray-700 rounded-lg shadow-sm border-gray-200 dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="index.php" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                                    Home
                                </a>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <span class="material-icons text-base outlined mx-2">chevron_right</span>
                                    <span class="ml-1 text-sm font-medium text-gray-400 md:ml-2 dark:text-gray-500">Top books</span>
                                </div>
                            </li>
                        </ol>
                        <form id="form-search" class="ml-auto w-5/12" method="post">
                            <input id="search" name="keyword" placeholder="Search for books" class="string form-input-control ml-auto required block px-4 py-2 rounded-md font-medium bg-gray-100 border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:shadow-md focus:border-gray-400 focus:bg-white my-2" type="text" />
                        </form>
                    </nav>

                </section>


                <div id="books-container" class="grid grid-col-4 grid-gap-2 my-8">

                    <?php

                    extract(get_books());

                    foreach ($result as $book) { ?>
                        <div class="w-56">
                            <a href="book.php?book_id=<?php echo $book["id"] ?>">
                                <img class="h-64 object-cover w-full" src="uploads/books/covers/<?php echo $book["book_cover"] ?>" alt="">
                                <p class="text-base text-indigo-600 mt-1"><?php echo $book["book_name"] ?></p>
                                <h4 class="text-sm text-gray-500"><?php echo $book["book_desc"] ?></h4>
                                <div class="flex justify-between">
                                    <h5 class="text-sm text-gray-400 mt-2">Author: <?php echo $book["book_author"] ?></h5>
                                    <h5 class="text-sm text-gray-400 mt-2"><?php echo $book["username"] ?></h5>
                                </div>
                            </a>
                        </div>
                    <?php }
                    ?>

                </div>

                <div id="books-paging" class="my-16 flex justify-end">
                    <?php
                    page_nav_links($paging, $_GET["search_kw"] ?? "");
                    ?>
                </div>
            </div>

            <div class="flex items-center flex-col justify-center overflow-hidden fixed inset-0 z-30" style="display: none;">
                <div class="absolute inset-0 bg-gradient-to-tr opacity-90 dark:from-gray-700 dark:via-gray-900 dark:to-gray-700 from-white via-gray-100 to-white"></div>
            </div>
        </div>
    </div>

    <?php include("inc/footer.php") ?>
</body>

<script>
    document.getElementById("form-search").addEventListener("submit", function(e) {
        e.preventDefault();

        const keyword = document.getElementById("search").value;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("books-container").innerHTML = JSON.parse(this.responseText)['books_html'].join(" ");
                document.getElementById("books-paging").innerHTML = JSON.parse(this.responseText)['paging_html'].join(" ");
            }
        };
        xmlhttp.open("GET", "inc/controller/book_controller.php?q=" + keyword, true);
        xmlhttp.send();
    })
</script>

</html>