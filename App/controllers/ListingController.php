<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use App\Models\Listing;
use Framework\Session;
use Framework\Authorization;


class ListingController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath("config/db.php");
        $this->db = new Database($config);
    }

    public function index()
    {


        $listings = $this->db->query("SELECT * FROM listings ORDER BY created_at DESC")->fetchAll();

        loadView('listings/index', [
            'listings' => $listings
        ]);
    }

    public function create()
    {
        loadView('listings/create');
    }

    public function show($params)
    {

        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        // Check if listing exists

        if (!$listing) {
            (new ErrorController())->notFound('Listing not found');
            return;
        }

        loadView('listings/show', [
            'listing' => $listing
        ]);
    }

    /**
     * Store data 
     * @return void
     */

    public function store()
    {

        $allowedFields = ['title', 'description', 'salary', 'requirements', 'benefits', 'company', 'address', 'city', 'tags', 'state', 'phone', 'email'];

        $newListingData = array_intersect_key($_POST, array_flip($allowedFields));

        $newListingData['user_id'] = $_SESSION['user']['id'];

        $newListingData = array_map('sanitize', $newListingData);

        $requiredFields = ['title', 'description', 'email', 'city', 'state', 'salary', 'company'];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($newListingData[$field]) || !Validation::string($newListingData[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        if (!empty($errors)) {

            loadView('listings/create', [
                'errors' => $errors,
                'listing' => $newListingData
            ]);

        } else {

            // insert into db

            $fields = [];

            foreach ($newListingData as $key => $value) {
                $fields[] = $key;  // Use $key instead of $field
            }

            $fields = implode(', ', $fields);

            $values = [];

            foreach ($newListingData as $field => $value) {
                if ($value === '') {
                    $newListingData[$field] = null;
                }
                $values[] = ':' . $field;
            }

            $values = implode(', ', $values);

            $query = "INSERT INTO listings ({$fields}) VALUES ({$values})";

            $this->db->query($query, $newListingData);

            Session::setFlashMessage('success_message', 'Listing created successfully.');

            redirect('/listings');

        }


    }

    /**
     * Delete a listing
     * @param array $params
     * @return void
     */

    public function destroy($params)
    {

        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();



        if (!$listing) {
            (new ErrorController())->notFound('Listing not found');
            return;
        }

        // auth

        if (!Authorization::isOwner($listing->user_id)) {

            Session::setFlashMessage('error_message', "You are not auth to delete this listing.");

            return redirect('/listings/' . $listing->id);

        }

        $this->db->query('DELETE FROM listings WHERE id = :id', $params);

        // set flash message

        Session::setFlashMessage('success_message', 'Listing deleted successfully');

        redirect('/listings');
    }

    public function edit($params)
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        // auth

        if (!Authorization::isOwner($listing->user_id)) {
            Session::setFlashMessage('error_message', 'You are not auth to update this listing.');
            return redirect('/listings/' . $listing->id);
        }

        // Check if listing exists
        if (!$listing) {
            (new ErrorController())->notFound('Listing not found');
            return;
        }

        loadView('listings/edit', [
            'listing' => $listing
        ]);
    }

    public function update($params)
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        // Check if listing exists
        if (!$listing) {
            (new ErrorController())->notFound('Listing not found');
            return;
        }

        // auth

        if (!Authorization::isOwner($listing->user_id)) {
            Session::setFlashMessage('error_message', 'You are not auth to update this listing');
            return redirect('/listings/' . $listing->id);
        }

        $allowedFields = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'company', 'state', 'phone', 'email', 'requirements', 'benefits'];

        $updateValues = [];
        $updateValues = array_intersect_key($_POST, array_flip($allowedFields));
        $updateValues = array_map('sanitize', $updateValues);

        $requiredFields = ['title', 'description', 'salary', 'email', 'city', 'state'];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($updateValues[$field]) || !Validation::string($updateValues[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        if (!empty($errors)) {
            loadView('listings/edit', [
                'listing' => $listing,
                'errors' => $errors
            ]);
            exit;
        } else {
            // Submit to database

            $updateFields = [];

            foreach (array_keys($updateValues) as $field) {
                $updateFields[] = "{$field} = :{$field}";
            }

            $updateFields = implode(', ', $updateFields);

            $updateQuery = "UPDATE listings SET $updateFields WHERE id = :id";

            $updateValues['id'] = $id;

            $this->db->query($updateQuery, $updateValues);

            Session::setFlashMessage('success_message', 'Listing updated');

            redirect('/listings/' . $id);
        }
    }

    public function search()
    {
        $keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';
        $location = isset($_GET['location']) ? trim($_GET['location']) : '';

        $query = "SELECT * FROM listings WHERE 1=1";
        $params = [];

        if (!empty($keywords)) {
            $query .= " AND (title LIKE :keywords OR description LIKE :keywords OR tags LIKE :keywords)";
            $params[':keywords'] = "%{$keywords}%";
        }

        if (!empty($location)) {
            $query .= " AND (city LIKE :location OR state LIKE :location)";
            $params[':location'] = "%{$location}%";
        }

        $query .= " ORDER BY created_at DESC";

        $listings = $this->db->query($query, $params)->fetchAll();

        loadView('listings/index', [
            'listings' => $listings,
            'keywords' => $keywords,
            'location' => $location
        ]);
    }



}