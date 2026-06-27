@extends('layouts.app')
@section('content')

<div class="container py-5">
    <div class="text-center no-print mb-4">
        <button onclick="window.print()" class="btn btn-premium rounded-pill fw-bold px-5 shadow-lg"><i class="fas fa-print me-2"></i> Print Official Document</button>
    </div>

    <!-- Certificate of Completion -->
    <div class="print-page bg-white shadow-2xl mx-auto position-relative print-certificate mt-0 d-flex flex-column justify-content-center align-items-center text-center p-4 p-md-5" 
         style="max-width: 1123px; min-height: 794px; border: 15px solid #1e293b; background: radial-gradient(circle at center, #ffffff 0%, #f8fafc 100%);">
        
        <div class="certificate-inner border border-2 border-opacity-25 w-100 h-100 p-5 d-flex flex-column justify-content-center align-items-center position-relative" style="border-color: #94a3b8 !important;">
            
            <div style="width: 80px; height: 80px; background: var(--primary-gradient); border-radius: 20px; display: flex; align-items: center; justify-content: center; color: white; margin: 0 auto; box-shadow: 0 10px 25px rgba(79, 70, 229, 0.4);">
                <i class="fas fa-graduation-cap fa-2x"></i>
            </div>
            
            <h1 class="mt-4 mb-0 text-dark" style="font-family: 'Georgia', serif; font-size: 3rem; font-weight: 700; letter-spacing: -1px;">Certificate of Outstanding Completion</h1>
            <p class="text-muted text-uppercase tracking-widest mt-2 fw-bold" style="letter-spacing: 5px;">BAPS-e.learn-LMS Authenticated Credential</p>
            
            <div class="my-5 w-100">
                <p class="lead text-muted mb-2 font-italic" style="font-style: italic;">This is to certify that</p>
                <h2 class="display-3 fw-bold text-dark text-capitalize" style="font-family: 'Georgia', serif; border-bottom: 2px solid #e2e8f0; max-width: 70%; margin: 0 auto; padding-bottom: 5px;">{{ $certificate->user->name }}</h2>
                
                <p class="lead text-muted mt-4 mb-2" style="font-style: italic;">has successfully mastered the coursework and achieved excellence in</p>
                <h3 class="fw-bold text-primary display-6">{{ $certificate->course->title }}</h3>
            </div>
            
            <div class="d-flex justify-content-between align-items-end mt-auto w-100 px-4">
                <!-- Signature 1: Kothari/VC -->
                <div class="text-center signature-block">
                    <div class="signature-line" style="border-bottom: 2px solid #1e293b; width: 220px; margin-bottom: 10px; height: 40px;"></div>
                    <p class="fw-bold text-dark mb-0 fs-5" style="font-family: 'Georgia', serif;">Hon. Kothari / VC</p>
                    <span class="small text-muted text-uppercase tracking-widest" style="font-size: 0.7rem;">University Head</span>
                </div>

                <!-- Signature 2: Admin/Dean -->
                <div class="text-center signature-block">
                    <div class="mb-3">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ $certificate->unique_code }}" alt="QR Validation" class="border p-1 bg-white shadow-sm rounded">
                    </div>
                    <div class="signature-line" style="border-bottom: 2px solid #1e293b; width: 220px; margin-bottom: 10px;"></div>
                    <p class="fw-bold text-dark mb-0 fs-5" style="font-family: 'Georgia', serif;">Admin / Dean</p>
                    <span class="small text-muted text-uppercase tracking-widest" style="font-size: 0.7rem;">Academic Affairs</span>
                </div>

                <!-- Signature 3: Teaching Faculty -->
                <div class="text-center signature-block">
                    <div class="signature-line" style="border-bottom: 2px solid #1e293b; width: 220px; margin-bottom: 10px; height: 40px;"></div>
                    <p class="fw-bold text-dark mb-0 fs-5" style="font-family: 'Georgia', serif;">{{ $certificate->course->instructor ?? 'Lead Instructor' }}</p>
                    <span class="small text-muted text-uppercase tracking-widest" style="font-size: 0.7rem;">Verified Faculty</span>
                </div>
            </div>

            <div class="position-absolute top-0 end-0 p-4">
                <p class="mb-0 text-muted" style="font-size: 0.7rem;">Date: <b>{{ $certificate->created_at->format('M d, Y') }}</b><br>ID: <b>{{ $certificate->unique_code }}</b></p>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print, .sidebar, .top-nav, footer { display: none !important; }
    body { background: white !important; margin: 0 !important; }
    @page { size: A4 landscape; margin: 0; }
    .container { max-width: none !important; width: 100% !important; padding: 0 !important; }
    .print-certificate {
        width: 296mm !important;
        height: 209mm !important;
        border: 10px solid #1e293b !important;
        box-shadow: none !important;
    }
    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
}
</style>

@endsection
