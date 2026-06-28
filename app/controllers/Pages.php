<?php
class Pages extends Controller {
    public function __construct() {
        // Nothing to load currently
    }

    public function index() {
        $data = [
            'title' => 'MedClinicPro - Secure Medical Clinic Management',
            'description' => 'A robust, bank-grade secure platform for managing multiple medical clinics.'
        ];
        $this->view('pages/index', $data);
    }

    public function services() {
        $data = [
            'title' => 'Services - MedClinicPro'
        ];
        $this->view('pages/services', $data);
    }

    public function features() {
        $data = [
            'title' => 'Features - MedClinicPro'
        ];
        $this->view('pages/features', $data);
    }

    public function faq() {
        $data = [
            'title' => 'FAQ - MedClinicPro'
        ];
        $this->view('pages/faq', $data);
    }

    public function contact() {
        $data = [
            'title' => 'Contact Us - MedClinicPro'
        ];
        $this->view('pages/contact', $data);
    }

    public function privacy() {
        $data = [
            'title' => 'Privacy Policy - MedClinicPro'
        ];
        $this->view('pages/privacy', $data);
    }

    public function terms() {
        $data = [
            'title' => 'Terms of Service - MedClinicPro'
        ];
        $this->view('pages/terms', $data);
    }
}
