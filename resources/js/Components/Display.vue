<template>
    <div class="content-1">
      <hr class="hr1">
      <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 flex flex-col sm:flex-row items-center">
        <!-- Image -->
        <img
          src="/images/logo.png"
          alt="Description of the image"
          class="w-full sm:w-auto max-w-md rounded-md shadow-lg mb-4 sm:mb-0"
        />

        <!-- Text content -->
        <div class="container2 text-center sm:text-left p-4 sm:p-10">
          <p class="text-lg sm:text-xl font-bold mb-4">
            The “Runway” Mannequin collection is a coterie of innovative mannequins in poses and styles.
          </p>
          <p class="text-base sm:text-lg mb-4">
            As one of the leading companies in the display mannequin business, Runway is able to offer at any time a wide range of contemporary and well-functioning mannequins within the categories of women, men and soon teenagers and children.
          </p>
          <p class="text-base sm:text-lg">
            Apart from “Runway” own design and product development program, we offer our customers a wide range of services: e.g. developing make-up and wig styles to meet specific wishes from our customers.
          </p>
        </div>
      </div>
      <hr class="hr1">
    </div>
  </template>

  <style>
    .content-1 {
      background-repeat: no-repeat;
      background-size: 100% auto;
      background-attachment: fixed;
      margin: 0;
      padding: 50px;
    }

    .container2 {
      background: rgba(0, 0, 0, 0);
      color: rgb(0, 0, 0);
      font-size: 40px;
      text-shadow: 1px 1px 1px rgb(241, 241, 241);
    }

    .hr1 {
      border-bottom: 5px solid black;
      margin-left: 150px;
      margin-right: 150px;
    }

    @media (max-width: 768px) {
      /* Responsive styles for screens smaller than 768px (e.g., mobile) */
      .content-1 {
        padding: 20px;
      }

      .container2 {
        font-size: 20px;
        text-shadow: 1px 1px 1px rgb(241, 241, 241);
      }

      .hr1 {
        margin-left: 20px;
        margin-right: 20px;
      }
    }
  </style>
