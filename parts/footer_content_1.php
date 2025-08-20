
<?php if( is_page('home') ):  ?>

        <article id="index_contact">
            <h2><span>Contact</span>お問い合わせ*</h2>
            <p class="lead">イントラックスに興味を持っていただきありがとうございます。<br>
            アユサ高校交換留学、オペアケア、インターンシップ、ワークトラベル、J1ビザ手配などについてのご不明点、ご質問などを受け付けております。<br>
            些細なことでも構いませんので、まずはお気軽にお問い合わせください。</p>
            <ul>
                <li><a id="a_link_2" href="tel:0334342729"><p><span>Tel</span>03-3434-2729</p></a></li>
                <li><a id="a_link_1" href="<?php echo str_replace('ayusa/','',network_site_url()); ?>common_form/"><p><span>Contact us</span>お問い合わせ</p></a></li>
            </ul>
        </article>

<?php else: ?>
        <article id="kasou_contact">
            <h2><span>Contact</span>お問い合わせ</h2>
            <p class="lead">イントラックスのプログラムにご興味を持っていただき、ありがとうございます。<br> まずはお気軽にお問合せください。</p>
            <ul>
                <li><a href="tel:0334342729"><p><span>Tel</span>03-3434-2729</p></a></li>
                <li><a href="<?php echo str_replace('ayusa/','',network_site_url()); ?>common_form/"><p><span>Contact us</span>お問い合わせ</p></a></li>
            </ul>
        </article>


<?php endif; ?>