<script type="text/html" class="alike-list">
  <% if( _.isObject(items) ){ %>
  
    <% _.each(items, function(item,key,list){ %>
      <div class="alike-widget-partials clearfix">
        <a href="<%= item.postLink %>" class="alike-widget-image">
          <img src="<%= item.postThumb %>" >
        </a>
        
        <h3 class="alike-widget-title<%= (item.postTitle.length < 14) ? ' alike-title-middle' : '' %>">
          <a href="<%= item.postLink %>">
            <%= item.postTitle %>
          </a>
        </h3>
        <div class="alike-widget-close remove-listing">
          <a href="#" class="alike-widget-remove" data-post-id="<%= item.postId %>"><i class="fas fa-close"></i></a>
        </div>
      </div>

    <% }) %>

  <%  } %>
  <% if( _.isEmpty(items) ){ %>
    <div class="alike-widget-partials clearfix"><p class="nomatches marB0"><?php echo esc_html__('No Items Selected.', 'alike') ?></p></div>
  <%  } %>
</script>
<div class="alike-widget-wrapper">
  <div class="alike-widget"></div>
  
  <div class="alike-widget-btn-wrap">
    <div class="col span_7 first">
      <a class="alike-button-compare alike-btn-compare btn" data-page-url="<?php echo esc_url(get_the_permalink($page_id)); ?>" href=""><?php echo esc_html__('Compare', 'alike') ?></a>  
    </div>
    <div class="col span_5">
      <a class="alike-button-clear alike-btn-clear btn btn-delete marT0" href=""><?php echo esc_html__('Clear', 'alike') ?></a>
    </div>
  </div>
</div>