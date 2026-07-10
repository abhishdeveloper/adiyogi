<?php
class Profile extends Controller {
    private $clinicModel;

    public function __construct() {
        $this->clinicModel = $this->model('Clinic');
    }

    // View public digital visiting card
    public function show($slug = '') {
        if(empty($slug)) {
            header('location: /');
            exit;
        }

        $clinic = $this->clinicModel->getClinicBySlug($slug);

        if(!$clinic || $clinic->is_verified == 0) {
            die("Profile not found or pending verification.");
        }

        $socials = json_decode($clinic->social_links, true) ?: [];

        $data = [
            'title' => htmlspecialchars($clinic->clinic_name) . ' - Profile',
            'clinic' => $clinic,
            'socials' => $socials,
            'seo_title' => !empty($clinic->seo_title) ? htmlspecialchars($clinic->seo_title) : htmlspecialchars($clinic->clinic_name) . ' - Profile',
            'seo_description' => !empty($clinic->seo_description) ? htmlspecialchars($clinic->seo_description) : htmlspecialchars($clinic->bio ?? ''),
            'seo_og_image' => !empty($clinic->seo_og_image) ? $clinic->seo_og_image : null
        ];

        // Determine which theme view to load
        $theme = $clinic->theme_preference;
        $allowed_themes = ['modern', 'classic', 'minimal'];

        if(!in_array($theme, $allowed_themes)) {
            $theme = 'modern';
        }

        parent::view('profile/themes/' . $theme, $data);
    }
}
