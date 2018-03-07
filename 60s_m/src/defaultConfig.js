import axios from 'axios'

export default {
  install(Vue) {
    Vue.prototype.$http = axios

    Vue.prototype.app_config = {
     basePath: './index.php/Index',
     // basePath: 'http://114.215.222.75:8006/index.php/Index',//
     // basePath: 'http://127.0.0.1:8008/server/index.php/Index',
      // basePath: 'http://127.0.0.1/index.php/Index',//
      staticPath: './static',
    };

    Vue.prototype.defaultData = {
      home_video: [{
        "id": "0",
        "img_url": "../../static/images/800.jpg",
        "title": "正在获取标题",
      }, {
        "id": "0",
        "img_url": "../../static/images/800.jpg",
        "title": "正在获取标题",
      }, {
        "id": "0",
        "img_url": "../../static/images/800.jpg",
        "title": "正在获取标题",
      }],
      special: [{
        "id": "0",
        "img_url": "../../static/images/loading-square.png",
        "title": "正在获取标题",
        "description": "正在获取描述信息......"
      }, {
        "id": "0",
        "img_url": "../../static/images/loading-square.png",
        "title": "正在获取标题",
        "description": "正在获取描述信息......"
      }, {
        "id": "0",
        "img_url": "../../static/images/loading-square.png",
        "title": "正在获取标题",
        "description": "正在获取描述信息......"
      }, {
        "id": "0",
        "img_url": "../../static/images/loading-square.png",
        "title": "正在获取标题",
        "description": "正在获取描述信息......"
      }],
      cate_data: {
        "breadcrumb_info": [],
        "video_info": {
          "img_url": "",
          "video_url": ""
        },
        "title": "",
        "subhead": "",
        "people": "",
        "description": "",
        "time_needed": "",
        "estimated_amount": "",
        "prompt_informaton": "",
        "materials": [],
        "step": []
      },
      contribute_list: {
        "allcount": 0,
        "list": []
      },
      comment_list: {
        "allcount": "0",
        "list": []
      },
      special_info: {
        "id": "0",
        "img_url": "../../static/images/loading-square.png",
        "title": "正在获取标题",
        "description": "正在获取描述信息......"
      },
    };
  }
}
