<?php
// contact.php
require_once 'session.php';
include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row bg-white rounded shadow-sm overflow-hidden">
        <div class="col-md-5 p-5 text-white" style="background-color: var(--primary-brown);">
            <h3 class="font-weight-bold mb-4">Get in touch</h3>
            <p class="mb-4">Have a question or want to order a custom cake? We'd love to hear from you.</p>
            
            <div class="mb-4">
                <h6 class="font-weight-bold text-warning">ADDRESS</h6>
                <p>1 Phan Tay Nhac, Tu Liem, Hanoi</p>
            </div>
            <div class="mb-4">
                <h6 class="font-weight-bold text-warning">PHONE</h6>
                <p>+84 987 654 321</p>
            </div>
            <div>
                <h6 class="font-weight-bold text-warning">EMAIL</h6>
                <p>support@bakeryhouse.com</p>
            </div>
        </div>

        <div class="col-md-7 p-5">
            <h3 class="font-weight-bold text-dark mb-4">Send us a message</h3>
            <form>
                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <input type="text" class="form-control" placeholder="Your Name">
                    </div>
                    <div class="col-md-6 mb-3">
                        <input type="email" class="form-control" placeholder="Your Email">
                    </div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Subject">
                </div>
                <div class="form-group">
                    <textarea class="form-control" rows="4" placeholder="Message"></textarea>
                </div>
                <button type="button" class="btn btn-brown px-4" onclick="alert('Message sent! (Demo)')">Send Message</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

