<?= loadPartial('head'); ?>
<?= loadPartial('navbar'); ?>

<section>
   <div class="container mx-auto p-4 mt-4">
      <div class="text-center text-3xl mb-4 font-bold border border-gray-300 p-3"><?= $status ?> Error</div>
      <p class="text-center text-2xl mb-4">
         <?= $message ?>
      </p>
      <div class="text-center">
         <a href="/" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded">Go Home</a>
      </div>
      <div class="text-center">
         <a href="/listings" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded">Back to listings</a>
      </div>
      
   </div>
</section>

<?= loadPartial('footer'); ?>