<div class="wrap">
    <h2><?php _e( 'تنظیمات', 'parseh-design' ); ?></h2>
    <?php settings_errors(); ?> 
    
    <div class="projects-about">
    	<h3>استودیو طراحی پارسه</h3>
        <p>سلام :)</p>
        <p>بابت نصب این پلاگین از شما ممنونیم</p>
        <p>این پلاگین که به صورت رایگان منتشر شده است به منظور راحتی شما عزیزان برای اضافه کردن قسمت نمونه کارها به وبسایت شما می باشد</p>
        <p>امیدواریم که از آن لذت ببرید</p>
        <p>لطفا نظرات و پیشنهادات سازنده خود را به ایمیل project@parseads.com برای ما بفرستید</p>
    </div>
    
    <form method="post" action="options.php">
        <?php wp_nonce_field( 'update-options' ); ?>

        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">تعداد ستون ها</th>
                    <td>
						<input type="number" name="projects_column" min="1" max="5" value="<?php echo get_option('projects_column'); ?>"/>
                        <p class="description">تعداد ستون های نمایش داده شده در صفحه لیست پروژه ها</p>
                    </td>
                </tr>
                <tr>
                	<th scope="row">فیلتر</th>
                    <td>
                    	<?php $projects_filter = get_option('projects_filter') ?>
                    	<input class="js-switch" type="checkbox" name="projects_filter" value="1" 
						<?php echo checked( 1, isset( $projects_filter ) ? $projects_filter : 0, false ) ?>/>
                        <p class="description">با فعال کردن این گزینه دسته بندی پروژه ها فعال می شود</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">نوع نمایش تصایر</th>
                    <td>
                    	<?php $projects_gallery_type = get_option('projects_gallery_type') ?>
                    	<select name="projects_gallery_type">
                        	<option value="1" <?php if ( $projects_gallery_type == 1 ) echo 'selected="selected"'; ?>>شبکه ای</option>
                            <option value="2" <?php if ( $projects_gallery_type == 2 ) echo 'selected="selected"'; ?>>اسلایدشو</option>
                        </select>
                        <p class="description">با تغییر این گزینه می توانید نوع نمایش تصاویر پروژه را در صفحه ادامه مطلب عوض کنید</p>
                    </td>
                </tr>
                <tr>
                	<th scope="row">وبسایت</th>
                    <td>
                    	<?php $projects_website = get_option('projects_website') ?>
                    	<input class="js-switch" type="checkbox" name="projects_website" value="1" 
						<?php echo checked( 1, isset( $projects_website ) ? $projects_website : 0, false ) ?>/>
                        <p class="description">با فعال کردن این گزینه لینک وبسایت پروژه اضافه می شود</p>
                    </td>
                </tr>
                <tr>
                	<th scope="row">کارفرما</th>
                    <td>
                    	<?php $projects_client = get_option('projects_client') ?>
                    	<input class="js-switch" type="checkbox" name="projects_client" value="1" 
						<?php echo checked( 1, isset( $projects_client ) ? $projects_client : 0, false ) ?>/>
                        <p class="description">با فعال کردن این گزینه کارفرما اضافه می شود</p>
                    </td>
                </tr>
                <tr>
                	<th scope="row">لایک</th>
                    <td>
                    	<?php $projects_like = get_option('projects_like') ?>
                    	<input class="js-switch" type="checkbox" name="projects_like" value="1" 
						<?php echo checked( 1, isset( $projects_like ) ? $projects_like : 0, false ) ?>/>
                        <p class="description">با فعال کردن این گزینه دکمه لایک به پروژه ها اضافه می شود</p>
                    </td>
                </tr>
                <tr>
                	<th scope="row">تاریخ اجرای پروژه</th>
                    <td>
                    	<?php $projects_date = get_option('projects_date') ?>
                    	<input class="js-switch" type="checkbox" name="projects_date" value="1" 
						<?php echo checked( 1, isset( $projects_date ) ? $projects_date : 0, false ) ?>/>
                        <p class="description">با فعال کردن این گزینه تاریخ اجرای پروژه به جزییات پروژه اضافه می شود</p>
                    </td>
                </tr>
                <tr>
                	<th scope="row">بازدید</th>
                    <td>
                    	<?php $projects_view = get_option('projects_view') ?>
                    	<input class="js-switch" type="checkbox" name="projects_view" value="1" 
						<?php echo checked( 1, isset( $projects_view ) ? $projects_view : 0, false ) ?>/>
                        <p class="description">با فعال کردن این گزینه تعداد بازدید پروژه اضافه می شود</p>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <p class="submit">
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="projects_column,projects_filter,projects_gallery_type,projects_website,projects_client,projects_like,projects_date,projects_view" />
        <input type="submit" class="button button-primary" name="Submit" value="ذخیره تغییرات" />
        </p>
    </form>
</div>