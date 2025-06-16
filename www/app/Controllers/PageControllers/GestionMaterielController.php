<?php

namespace App\Controllers\PageControllers;

use Exception;
use App\Controllers\Controller;
use App\Enums\EtatMateriel;
use App\Models\Impl\MaterielDAOImpl;
use App\Models\Impl\CategorieDAOImpl;
use App\Models\Interfaces\MaterielDAO;
use App\Models\Interfaces\CategorieDAO;
use App\Models\Impl\TypeMaterielDAOImpl;
use App\Models\Interfaces\TypeMaterielDAO;

class GestionMaterielController extends Controller
{
    private CategorieDAO $categorieDAO;
    private TypeMaterielDAO $typeMaterielDAO;
    private MaterielDAO $materielDAO;

    public function __construct()
    {
        parent::__construct();
        $this->categorieDAO = new CategorieDAOImpl();
        $this->typeMaterielDAO = new TypeMaterielDAOImpl();
        $this->materielDAO = new MaterielDAOImpl();
    }

    public function index()
    {
        $categories = $this->categorieDAO->findAll();
        $typeMateriels = $this->typeMaterielDAO->findAll();

        $this->view('GestionMaterielView', [
            'title' => "Gestion du matériel",
            'categories' => $categories,
            'typeMateriels' => $typeMateriels,
        ]);
    }

    public function getCards()
    {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo '<div class="alert alert-error">Méthode invalide</div>';
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || empty($data['type_id'])) {
            echo '<div class="alert alert-error">Paramètre manquant</div>';
            return;
        }

        $typeId = intval($data['type_id']);

        $typeMateriel = $this->typeMaterielDAO->findById($typeId);
        if (!$typeMateriel) {
            echo '<div class="alert alert-error">Type de matériel non trouvé</div>';
            return;
        }

        $numberOfMateriel = $this->materielDAO->findNumberOfMaterielWithSameType($typeId);
        if (is_null($numberOfMateriel)) {
            $numberOfMateriel = 0; // On considère qu'il n'y en a aucun si non trouvé
        }

        $numberOfCards = $typeMateriel->getQuantite() - $numberOfMateriel;

        if ($numberOfCards <= 0) {
            echo '<div class="alert alert-info">Tous les matériels sont déjà enregistrés.</div>';
            return;
        }

        $listOfEtatMaterielFromEnum = EtatMateriel::getArrayOfEnumValue();

        $html = "";

        for ($i = 0; $i < $numberOfCards; $i++) {
            $html .= '<div class="col-md-4 mb-3 card-item">
        <div class="card">
            <div class="card-body">
                <form class="materialForm">
                    <input type="hidden" name="type_id" value="' . htmlspecialchars($typeId) . '">
                    <div class="mb-3">
                        <label>Référence</label>
                        <input type="text" name="reference" class="form-control" required>
                    </div>
                    <div class="mb-3">
            <label for="etatMateriel" class="form-label">État</label>
            <select name="etat" class="form-select">';

            foreach ($listOfEtatMaterielFromEnum as $value) {
                $html .= '<option value="' . htmlspecialchars($value) . '">' . htmlspecialchars($value) . '</option>';
            }

            $html .= '      </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>';
        }


        echo $html;
    }
}
