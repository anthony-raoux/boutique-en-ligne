    <?php
    session_start();
    require_once 'config/Database.php';
    require_once 'controllers/ProductController.php';

    $productController = new ProductController();
    $database = new Database();
    $db = $database->connect();

    $data = $_POST;
    $product_id = $data['product_id'] ?? null;

    if (!$product_id) {
        echo json_encode(['success' => false, 'error' => 'ID de produit manquant']);
        exit;
    }

    // Vérifier si le produit existe dans la base de données
    $productResult = $productController->getProductById($product_id);
    $product = $productResult['product'] ?? null;

    if (!$product) {
        echo json_encode(['success' => false, 'error' => 'Produit non trouvé']);
        exit;
    }

    // Ajouter le produit au panier en session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = ['quantity' => 1];
    } else {
        $_SESSION['cart'][$product_id]['quantity']++;
    }

    // Récupérer l'ID de l'utilisateur (à adapter selon votre gestion d'authentification)
    $user_id = 1;

    // Si l'utilisateur est connecté, mettre à jour le panier en base de données
    if ($user_id) {
        try {
            $db->beginTransaction();

            // Supprimer d'abord les entrées existantes pour cet utilisateur et ce produit
            $queryDelete = "DELETE FROM panier WHERE user_id = :user_id AND product_id = :product_id";
            $stmtDelete = $db->prepare($queryDelete);
            $stmtDelete->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmtDelete->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmtDelete->execute();

            // Insérer le nouveau panier
            $queryInsert = "INSERT INTO panier (user_id, product_id, quantity, created_at) 
                            VALUES (:user_id, :product_id, :quantity, NOW())";
            $stmtInsert = $db->prepare($queryInsert);
            $stmtInsert->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmtInsert->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmtInsert->bindParam(':quantity', $_SESSION['cart'][$product_id]['quantity'], PDO::PARAM_INT);
            $stmtInsert->execute();

            $db->commit();
        } catch (PDOException $e) {
            $db->rollBack();
            echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'ajout au panier: ' . $e->getMessage()]);
            exit;
        }
    }

    echo json_encode(['success' => true, 'message' => 'Produit ajouté au panier avec succès']);
    ?>
