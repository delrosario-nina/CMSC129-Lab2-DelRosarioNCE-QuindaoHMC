# 🍽️ Recipe Diary

A personal recipe management web app built with **Laravel 12** for everyday cooking needs. In partial fulfillment of academic requirements for **CMSC 129** at the **University of the Philippines Visayas** by Nina Claudia Del Rosario and Hansen Maeve Quindao.

## ✨ Features
* **CRUD:** Create, read, edit, and delete recipes
* **Step-by-Step Cook Mode:** An interface modal that goes over each process step by step
* **Tags:** Categorize recipes by Complexity, Time, Type, Protein, and Mastery Status
* **Soft Delete:** Restore or permanently delete your recipes in the Recipe Graveyard (Trash)
* **File Upload with Storage Management:** Add images to all of your recipes
* **Personalization:** Editable diary name saved in `localStorage`

---

## 🚀 Installation & Setup

### **Prerequisites**
* PHP 8.2+
* Composer
* Node.js 20+ & npm
* **PostgreSQL** installed and running

#### **1. Clone & Install**
```bash
git clone https://github.com/delrosario-nina/CMSC129-Lab2-DelRosarioNCE-QuindaoHMC.git
cd your-repo-name
composer install
npm install
```

#### **2. Database Setup (PostgreSQL)**
- Open PostgreSQL terminal (`psql`)
- Create a new database for the project:   
    ```sql
    CREATE DATABASE ninas_recipe_diary;
    ```
- Copy the environment file:   
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
- Update your `.env` file with your PostgreSQL credentials:   
    ```env
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=ninas_recipe_diary
    DB_USERNAME=YOUR_USERNAME
    DB_PASSWORD=YOUR_PASSWORD
    ```

#### **3. Migrations & Assets**
Commands to set up the table structures and seed initial data:
```bash
# Run migrations and pre-fill categories
php artisan migrate --seed

# Storage link for image uploads
php artisan storage:link

# Build the frontend assets
npm run build
```

---

## 🧑‍💻 Running Locally

1.  **Terminal 1 (Server):** `php artisan serve`
2.  **Terminal 2 (Queue):** `php artisan queue:listen` (Handles background tasks)
3.  **Terminal 3 (Vite):** `npm run dev` (Hot-reloads CSS/JS)

APP URL: **`http://localhost:8000`** 

---

## 🏗️ MVC Architecture & Project Structure

### **1. Models**
Handles database interactions, data integrity, and relationships.
| File | Responsibility |
| :--- | :--- |
| `app/Models/Recipe.php` | **Core Model**; manages `SoftDeletes` and defines relationships with Ingredients, Steps, and Categories |
| `app/Models/Category.php` | Handles the tags (Protein, Complexity, Time, Type, Status) |
| `app/Models/Ingredient.php` | Manages the list of items for each recipe |
| `app/Models/Step.php` | Stores instructions |

### **2. Views**
Built using **Blade** and **Tailwind CSS v4**, for UI/UX
| Directory / File | Responsibility |
| :--- | :--- |
| `resources/views/layouts/app.blade.php` | **Master Template**; for global navigation and CSS/JS injections. |
| `resources/views/recipes/index.blade.php` | **Homepage** / main grid view for all recipes |
| `resources/views/recipes/show.blade.php` | **Cook Mode** / Detailed step by step view |
| `resources/views/components/` | Reusable UI elements for design consistency |

### **3. Controllers**
Handles the communication between data and interface
| File | Responsibility |
| :--- | :--- |
| `app/Http/Controllers/RecipeController.php` | **Primary Controller**; handles recipe retrieval, form validation, image upload processing, routing |
